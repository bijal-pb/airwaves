<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Resources\GroupResource;

class EventController extends BaseController
{/**
	 *  @OA\Post(
	 *     path="/api/create-event",
	 *     tags={"Create Event"},
	 *     summary="Create event",
	 *     security={{"bearer_token":{}}},
	 *     operationId="create-event",
     * 
	 * 
	 *     @OA\Parameter(
	 *         name="name",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
     *     @OA\Parameter(
	 *         name="description",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
     *     @OA\Parameter(
	 *         name="location",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
	 *     @OA\Parameter(
	 *         name="lat",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
	 *     @OA\Parameter(
	 *         name="lang",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
     *      @OA\Parameter(
	 *         name="time",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="time"
	 *         )
	 *     ),
     *   @OA\Parameter(
	 *         name="date",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="date"
	 *         )
	 *     ),   
     *       
     *     @OA\RequestBody(
    *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="event_photo",
    *                      description="event_photo",
    *                      type="file",
    *                      @OA\Items(type="string", format="binary")
    *                   ),
    *               ),
    *           ),
    *       ),
    *   @OA\Parameter(
	 *         name="group_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="group id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function create_event(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'group_id' => 'required',
            'event_photo' => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        } 
		$filename = null;
		$group = Group::find($request->group_id);
		if($group->create_event == 2)
		{
			if($group->created_by != Auth::id())
			{
				return $this->sendError('You have no permission for create event!', null,200);
			}
		}
        if($request->hasfile('event_photo'))
         {
            $file = $request->file('event_photo');
            $filename = time().$file->getClientOriginalName();
            $file->move(public_path().'/eventimages/', $filename);  
		}
		try{
			$event = new Event;
			$event->group_id = $request->group_id;
			$event->event_by = Auth::id();
			$event->name = $request->name;
			$event->description = $request->description;
			$event->location = $request->location;
			$event->lat = $request->lat;
			$event->lang = $request->lang;
			$event->event_time  = $request->time;
			$event->event_date = $request->date;
			$event->event_date_time = $request->date .' '.$request->time;
			$event->event_photo = $filename;
			$event->save();
			$event =  new EventResource($event);
			return $this->sendResponse($event, 'Event is added successfully!.');         
		}catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
        
    }
	     
    /**
     *  @OA\Get(
     *     path="/api/get-event",
     *     tags={"get event"},
     *     summary="get event list",
     *     security={{"bearer_token":{}}},
     *     operationId="get event",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     **/
    public function get_event(Request $request)
    {
        //$event = Event::with('Group')->get(); 
        $event = Event::with('event_created_by')->get();       
        $data =  EventResource::collection($event);
        return $this->sendResponse($data, 'Event data.');
    }


     /**
	 *  @OA\Post(
	 *     path="/api/delete-event",
	 *     tags={"event delete"},
	 *     summary="Event delete",
	 *     security={{"bearer_token":{}}},
	 *     operationId="event-delete",
	 * 
	 *     @OA\Parameter(
	 *         name="event_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="event id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
	 * )
	**/
    public function delete_event(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'event_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
                $event_delete = Event::find($request->event_id);
                if($event_delete)
                {
                    $event_delete->delete();
                    return $this->sendResponse(null, 'Event deleted successfully!');
                }
                return $this->sendError('Enter valid event id!.','',200);
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }


    
    /**
     *  @OA\Post(
	 *     path="/api/edit-event",
     *     tags={"Edit Event"},
	 *     summary="Edit event",
	 *     security={{"bearer_token":{}}},
     *     operationId="edit-event",
     *    
	 *     @OA\Parameter(
	 *         name="event_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="event id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
     *  @OA\Parameter(
	 *         name="name",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
     *     @OA\Parameter(
	 *         name="description",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
     *     @OA\Parameter(
	 *         name="location",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
	 **     @OA\Parameter(
	 *         name="lat",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
	 *     @OA\Parameter(
	 *         name="lang",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="text"
	 *         )
	 *     ),   
     *      @OA\Parameter(
	 *         name="time",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="time"
	 *         )
	 *     ),
     *   @OA\Parameter(
	 *         name="date",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="date"
	 *         )
	 *     ),   
     *       
     *     @OA\RequestBody(
    *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="event_photo",
    *                      description="event_photo",
    *                      type="file",
    *                      @OA\Items(type="string", format="binary")
    *                   ),
    *               ),
    *           ),
    *       ),
     *    
     *   @OA\Response(
	 *         response=200,
	 *         description="Success",
	 *         @OA\MediaType(
	 *             mediaType="application/json",
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized"
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid request"
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="not found"
	 *     ),
     * )
     */
    public function edit_event(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'event_id' => 'required',
            'event_photo' => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);
        if($validator->fails())
		{
		 return $this->sendError($validator->messages()->first(),null,200);
		}
		$filename = null;
		if($request->hasfile('event_photo')) {
		 $file = $request->file('event_photo');
		 $filename = time().$file->getClientOriginalName();
		 $file->move(public_path().'/eventimages/', $filename);  
		}
		$event = Event::find($request->event_id);
		
        $event->name = $request->name;
        $event->description = $request->description;
		$event->location = $request->location;
		$event->lat = $request->lat;
		$event->lang = $request->lang;
        $event->event_time  = $request->time;
		$event->event_date = $request->date;
		$event->event_date_time = $request->date .' '.$request->time;
		if($request->hasfile('event_photo'))
		{
			$event->event_photo = $filename;
		}
		$event->save();
		$data = new EventResource($event);
		return $this->sendResponse($data, 'Event updated successfully.');
	}



	
	
}
