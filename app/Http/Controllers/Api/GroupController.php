<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Genres;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Event;
use App\Http\Resources\UserResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\GroupUsersResource;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;


class GroupController extends BaseController
{ 
   /**
	 *  @OA\Post(
	 *     path="/api/create-group",
	 *     tags={"Create Group"},
	 *     summary="Create group",
	 *     security={{"bearer_token":{}}},
	 *     operationId="create-group",
	 * 
	 *     @OA\Parameter(
	 *         name="name",
	 *         in="query",
	 * 		   required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="description",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="string"
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
	 *             type="string"
	 *         )
	 *     ),  
	 *     @OA\Parameter(
	 *         name="lang",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),  
	 *      @OA\Parameter(
	 *         name="required_join",
	 *         in="query",
	 * 	 	   description="yes | no",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),   
	 *     @OA\RequestBody(
    *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="photo",
    *                      description="group photo",
    *                      type="file",
    *                      @OA\Items(type="string", format="binary")
    *                   ),
	* 				),
    *           ),
    *       ),
     *    @OA\Parameter(
	 *         name="genres_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="genres id",
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
    public function create_group(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'nullable|mimes:jpeg,jpg,png,gif',
			'genres_id' => 'required',
			'name' => 'required',
			'required_join' => 'required|in:yes,no',
           
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        } 
        $filename = null;
        if($request->hasfile('photo'))
         {
            $file = $request->file('photo');
            $filename = time().$file->getClientOriginalName();
            $file->move(public_path().'/groupimages/', $filename);  
		}
		DB::beginTransaction();
		try{
			$group = new Group;
			$group->created_by = Auth::id();
			$group->genres_id = $request->genres_id;
			$group->name = $request->name;
			$group->description = $request->description;
			$group->location = $request->location;
			$group->lat = $request->lat;
			$group->lang = $request->lang;
			$group->required_join = $request->required_join;
			$group->photo = $filename;
			if($group->save())
			{
				$ug=new GroupUser;
				$ug->group_id=$group->id;
				$ug->user_id= Auth::id();
				$ug->is_admin=1;
				$ug->is_join=1;
				$ug->save();
			}
			$group =  new GroupResource($group);
			DB::commit();
        	return $this->sendResponse($group, 'Group data is added successfully!.'); 
		}catch(Exception $e)
        {
            DB::rollBack();
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
	     
/**
     *  @OA\Get(
     *     path="/api/get-group",
     *     tags={"Get group"},
     *     summary="get group list",
     *     security={{"bearer_token":{}}},
     *     operationId="get group",
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
    public function get_group(Request $request)
    {
         $group = Group::with(['groupuser','genres'])->get();         
         $data =  GroupResource::collection($group);
        return $this->sendResponse($data, 'Group list.');
    }
/**
	 *  @OA\Post(
	 *     path="/api/delete-group",
	 *     tags={"Group delete"},
	 *     summary="group delete",
	 *     security={{"bearer_token":{}}},
	 *     operationId="group-delete",
	 * 
	 *     @OA\Parameter(
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
    public function delete_group(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'group_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
                $group_delete = Group::find($request->group_id);
                if($group_delete)
                {
                    $group_delete->delete();
                    return $this->sendResponse(null, 'Group deleted successfully!');
                }
                return $this->sendError('Enter valid Group id!.','',200);
        }catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
   /**
	 *  @OA\Post(
	 *     path="/api/edit-group",
     *     tags={"Edit Group"},
	 *     summary="Edit group",
	 *     security={{"bearer_token":{}}},
     *     operationId="edit-group",
	 *     @OA\Parameter(
	 *         name="group_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="group id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
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
	 *             type="string"
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
	 *             type="string"
	 *         )
	 *     ),  
	 *     @OA\Parameter(
	 *         name="lang",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),     
     *      @OA\Parameter(
	 *         name="required_join",
	 *         in="query",
	 * 	 	   description="yes | no",				
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),   
    *    @OA\RequestBody(
    *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="photo",
    *                      description="group photo",
    *                      type="file",
    *                      @OA\Items(type="string", format="binary")
    *                   ),
    *               ),
    *           ),
    *       ),
	 *    @OA\Parameter(
	 *         name="genres_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="genres id",
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
    public function edit_group(Request $request)
	{
		$validator = Validator::make($request->all(),[
            'group_id' => 'required',
			'genres_id' => 'required',
			'name' => 'required',
			'required_join' => 'required|in:yes,no',
			'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:100000'
		]);
 
		if($validator->fails())
		{
		 return $this->sendError($validator->messages()->first(),null,200);
		}
		if($request->hasfile('photo')) {
		 $file = $request->file('photo');
		 $filename = time().$file->getClientOriginalName();
		 $file->move(public_path().'/groupimages/', $filename);  
		}
		try {
			$group = Group::find($request->group_id);
			$group->genres_id = $request->genres_id;
			$group->name = $request->name;
			$group->description = $request->description;
			$group->location = $request->location;
			$group->lat = $request->lat;
			$group->lang = $request->lang;
			$group->required_join = $request->required_join;
			if($request->hasfile('photo')) {
				$group->photo = $filename;
			}
			$group->save();
			$data = new GroupResource($group);
			return $this->sendResponse($data, 'Group updated successfully.');
		}catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
		
		
	}
    
	/**
	 *  @OA\Post(
	 *     path="/api/join-group",
	 *     tags={"Join Group"},
	 *     summary="join group",
	 *     security={{"bearer_token":{}}},
	 *     operationId="join-group",
	 * 
     *     @OA\Parameter(
	 *         name="group_id",
	 *         in="query",			
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),   
     *     @OA\Parameter(
	 *         name="user_id",
	 *         in="query",			
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
    public function join_group(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'group_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		} 
		try {
			$group = Group::find($request->group_id);
			$gu = GroupUser::where('group_id',$request->group_id)
					->where('user_id',Auth::id())
					->first();
			if($gu)
			{
				if($group->required_join == 'yes')
				{
					$gu->is_join = 0;
					$gu->save();
					$msg = 'You have requested to join this group.';
					return $this->sendResponse($gu, $msg);
				}else {
					$gu->is_join = 1;
					$gu->save();
					$msg = 'You have joined this group successfully.';
					return $this->sendResponse($gu, $msg);
				}
			}
			$ug = new GroupUser;
			$ug->group_id=$request->group_id;
			$ug->user_id= Auth::id();
			$ug->is_admin= 0;
			if($group->required_join == 'yes')
			{
				$ug->is_join = 0;
				$msg = 'You have requested to join this group.';
			}else {
				$ug->is_join = 1;
				$msg = 'You have joined this group successfully.';
			}
			$ug->save();
			$ug =  new GroupUsersResource($ug);
			return $this->sendResponse($ug, $msg);
		} catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
		       
    }

	/**
	 *  @OA\Post(
	 *     path="/api/exit-group",
	 *     tags={"Exit Group"},
	 *     summary="Exit group",
	 *     security={{"bearer_token":{}}},
	 *     operationId="group-delete",
	 * 
	 *     @OA\Parameter(
	 *         name="group_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="group id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 * 
	 *     @OA\Parameter(
	 *         name="user_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="user id",
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
    public function exit_group(Request $request)
    {
        $validator = Validator::make($request->all(),[  
			'group_id' => 'required',
			'user_id' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
        }
        try {
                $gu_delete = GroupUser::where('user_id',$request->user_id)->where('group_id',$request->group_id)->first();
                if($gu_delete)
                {
                    $gu_delete->delete();
					if($request->user_id == Auth::id())
					{
						return $this->sendResponse(null, 'You have left this group');
					}
                    return $this->sendResponse(null, 'User is removed from group successfully !');
                }
                return $this->sendError('Enter valid  id!.','',200);
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
    }
    /**
     *  @OA\Get(
     *     path="/api/get-group-detail",
     *     tags={"get group detail"},
     *     summary="get group detail list",
     *     security={{"bearer_token":{}}},
     *     operationId="get group detail",
	 * 
     *    @OA\Parameter(
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
     * )
     **/
    public function get_group_detail(Request $request)
    {
		$validator = Validator::make($request->all(),[
            'group_id' => 'required',     
	     ]);
         if($validator->fails())
          	{
          		return $this->sendError($validator->messages()->first(),null,200);
			} 
		$g = Group::find($request->group_id);
		if($g->required_join == 'yes'){
			$group = Group::with(['created_user','genres','Event','GroupUser' => function($q) {
								return $q->where('is_join',1);
							}])	
							->withCount('PendingRequest')						
							->find($request->group_id);
		}else{
			$group = Group::with(['created_user','genres', 'GroupUser','Event'])
					->withCount('PendingRequest')	
					->find($request->group_id);

		}
		
        return $this->sendResponse($group,'Group detail.');
	}
	/**
	 *  @OA\Post(
	 *     path="/api/get-group-requests",
	 *     tags={"Get Group Requests"},
	 *     summary="Get group requests",
	 *     security={{"bearer_token":{}}},
	 *     operationId="get-group-requests",
	 * 
	 *     @OA\Parameter(
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
	public function get_group_requests(Request $request)
	{
		$validator = Validator::make($request->all(),[
            'group_id' => 'required',     
		]);
		if($validator->fails())
		{
		return $this->sendError($validator->messages()->first(),null,200);
		} 
		$group_users = GroupUser::with('user')
						->where('group_id',$request->group_id)
						->where('is_join',0)->get();
		return $this->sendResponse($group_users,'Group users request.');
	}
	/**
	 *  @OA\Post(
	 *     path="/api/accept-group-request",
	 *     tags={"accept Group Requests"},
	 *     summary="accept group requests",
	 *     security={{"bearer_token":{}}},
	 *     operationId="get-group-requests",
	 * 
	 *     @OA\Parameter(
	 *         name="group_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="group id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="user_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="user id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="is_join",
	 *         in="query",
	 *         required=true,
	 * 		   description="1- accept | 2 - reject",
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
	public function accept_group_request(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'group_id' => 'required',
			'user_id'  => 'required',
			'is_join' => 'required|in:1,2'
		]);
		if($validator->fails())
		{
		return $this->sendError($validator->messages()->first(),null,200);
		} 
		$group_user = GroupUser::where('group_id',$request->group_id)
						->where('user_id',$request->user_id)->first();
		if($group_user)
		{
			$group_user->is_join = $request->is_join;
			$group_user->save();
			if($request->is_join == 1)
			{
				return $this->sendResponse('','User is added to group successfully.');
			}else {
				return $this->sendResponse('','User request is declined successfully.');
			}
		}
		return $this->sendError('Something went wrong, Please try again!.','',200);
	}
	/**
     *  @OA\Get(
     *     path="/api/meetups",
     *     tags={"get meetups"},
     *     summary="get meetups list",
     *     security={{"bearer_token":{}}},
     *     operationId="get meetups",
	 * 
	 * 	   @OA\Parameter(
	 *         name="genres_id",
	 *         in="query",
	 * 		   description="genres_id",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 * 	   @OA\Parameter(
	 *         name="keyword",
	 *         in="query",
	 * 		   description="Search keyword",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 * 	   @OA\Parameter(
	 *         name="lat",
	 *         in="query",
	 * 		   description="latitude",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 * 	   @OA\Parameter(
	 *         name="lang",
	 *         in="query",
	 * 		   description="longitude",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 * 	  @OA\Parameter(
	 *         name="event_active",
	 *         in="query",
	 * 		   description="1-active | 0-inactive",
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
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
	public function meetups(Request $request)
	{
		// $group_ids = GroupUser::where('user_id',Auth::id())->where('is_join',1)->pluck('group_id');
		$groups = Group::query();
	
		if($request->event_active == 1)
		{
			$groups = $groups->whereHas('event', function($q){
				$q->where('event_date_time', '>=', Carbon::now()->format('Y-m-d H:i:s'));
			});
		}else {
			// $groups = $groups->with('event', function($q){
			// 	$q->where('event_date_time', '>=', Carbon::now()->format('Y-m-d H:i:s'));
			// });
			$groups = $groups->with('event');
		}
		if($request->keyword != null)
		{
			$groups = $groups->where('name','LIKE', '%' . $request->keyword . '%');
		}
		if($request->genres_id != null)
		{
			$generes = explode(',',$request->genres_id);
			$groups = $groups->whereIn('genres_id',$generes);
		}
		if($request->lat != null && $request->lang != null)
		{
			$latitude = $request->lat;
			$longitude = $request->lang;
			$distance = 10;
			$groups = $groups->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(lat) ) * cos( radians(lang) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(lat) ) ) ) <= $distance");
		}

		$groups = $groups->paginate(100);
		foreach($groups as $g)
		{
			$g->total_event = count($g->event) > 0 ? count($g->event) : 0;
		}
		$group = GroupResource::collection($groups);
		return $this->sendResponse($group,'meetup list.');
	}
	/**
	 *  @OA\Post(
	 *     path="/api/group/event-create/enable",
	 *     tags={"create event enable disable for group"},
	 *     summary="create event enable disable for group",
	 *     security={{"bearer_token":{}}},
	 *     operationId="create event enable disable for group",
	 * 
	 *     @OA\Parameter(
	 *         name="group_id",
	 *         in="query",
	 *         required=true,
	 * 		   description="group id",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="status",
	 *         in="query",
	 *         required=true,
	 * 		   description="1- create event enable | 2 - create event disable",
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
	public function event_create_enable(Request $request){
		$validator = Validator::make($request->all(),[
			'status' => 'required|in:1,2',
			'group_id' => 'required'
		]);
		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$group = Group::find($request->group_id);
			if($group->created_by == Auth::id())
			{
				$group->create_event = $request->status;
				$group->save();
				if($request->status == 1){
					$msg = "Event create enabled successfully!";
				}else{
					$msg = "Event create disabled successfully!";
				}
				return $this->sendResponse($group, $msg);
			}
			return $this->sendError('You have no permission for edit this group!', null,200);
			
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e->getMessage(),200);
		}
	}
	
}
