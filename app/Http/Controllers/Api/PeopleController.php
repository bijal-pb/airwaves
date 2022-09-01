<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\MatchUser;
use App\Models\UserTrack;
use App\Http\Resources\PeopleResource;


class PeopleController extends BaseController
{
    /**
	 *  @OA\Get(
	 *     path="/api/explore",
	 *     tags={"explore people"},
	 *     summary="Explore Peoples",
	 *     security={{"bearer_token":{}}},
	 *     operationId="explore",
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
    public function explore(Request $request)
    {
        $user = User::find(Auth::id());
        $latitude = $user->latitude;
		$longitude = $user->longitude;
		$setting = Setting::latest()->first();
		$distance = $setting->distance;
		// $distance = 20;

        $match_users = MatchUser::select('match_users.user_id2')
                        ->where('match_users.user_id1',Auth::id())
						->where('match_users.status',2);
        
        $match_user2 = MatchUser::select('match_users.user_id1')
                        ->where('match_users.user_id2',Auth::id())
						->where('match_users.status',2);

        $matchs = $match_user2->union($match_users)->pluck('user_id1');

        // return $matchs;

        if($latitude == null)
        {
            return $this->sendError('Your location is not set, please set!','',200);
        }
        try {
            $explores = User::with('tracks')->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'users.*')
                ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->where('id','!=',Auth::id())
                ->whereNotIn('users.id',$matchs)
                ->orderBy('distance','asc')
                ->paginate(10);
			
            $data = PeopleResource::collection($explores);
            return $this->sendResponse($data, 'Explore people list!.');
        } catch(Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.',$e,200);
        }
        
    }

    /**
	 *  @OA\Get(
	 *     path="/api/match",
	 *     tags={"match people"},
	 *     summary="Match Peoples",
	 *     security={{"bearer_token":{}}},
	 *     operationId="match",
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
    public function match(Request $request)
    {
        // $match_users = MatchUser::select('u2.*')
        //                 ->join('users as u2','match_users.user_id2','=','u2.id')
		// 				->where('match_users.user_id1',Auth::id())
		// 				->where('match_users.status',2);
        
        // $match_user2 = MatchUser::select('u1.*')
        //                 ->join('users as u1','match_users.user_id1','=','u1.id')
		// 				->where('match_users.user_id2',Auth::id())
		// 				->where('match_users.status',2);

		$match_users = MatchUser::select('match_users.user_id2')
                        ->where('match_users.user_id1',Auth::id())
						->where('match_users.status',2);
        
        $match_user2 = MatchUser::select('match_users.user_id1')
                        ->where('match_users.user_id2',Auth::id())
						->where('match_users.status',2);

        $matchs = $match_user2->union($match_users)->pluck('user_id1');

		$match_users = User::with('tracks')
						->whereIn('id',$matchs)
						->paginate(10);
                    
		$match_users = PeopleResource::collection($match_users);
		return $this->sendResponse($match_users, 'Match people list!.');
    }

	/**
	 *  @OA\Post(
	 *     path="/api/match-request",
	 *     tags={"match request"},
	 *     summary="Match request",
	 *     security={{"bearer_token":{}}},
	 *     operationId="match-request",
	 * 
	 *     @OA\Parameter(
	 *         name="match_to",
	 *         in="query",
	 *         required=true,
	 * 		   description="id",
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
    public function match_request(Request $request)
    {
        $validator = Validator::make($request->all(),[
			'match_to' => 'required',
        ]);
        if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		$check_match = MatchUser::where('user_id1',$request->match_to)
								->where('user_id2',Auth::id())
								->first();
		if(isset($check_match))
		{
			if($check_match->status == 1)
			{
				try{
					$check_match->status = 2;
					$check_match->action_user_id = Auth::id(); 
					$check_match->save();
					$data = null;
					$user = User::find($request->match_to);
					$login_user = User::find(Auth::id());
					sendPushNotification($user->device_token,$user->device_type,'You have matched with '.$login_user->name,'You have matched with '.$login_user->name,1,$request->match_to);
					return $this->sendResponse($data, 'It’s a Match! you can now chat');

				} catch(Exception $e)
				{
					return $this->sendError('Something went wrong, Please try again!.',$e,200);
				}
			} else {
				$data = null;
				return $this->sendResponse($data, 'Already Match!.');
			} 
		}
		$check_match1 = MatchUser::where('user_id2',$request->match_to)
								->where('user_id1',Auth::id())
								->first();
		if(isset($check_match1))
		{
			if($check_match1->status == 1)
			{
				$data = null;
				return $this->sendResponse($data, 'Match request already sent.');
			} else {
				$data = null;
				return $this->sendResponse($data, 'Already Match!.');
			} 
		}
		try {
			$new_match = new MatchUser;
			$new_match->user_id1 = Auth::id();
			$new_match->user_id2 = $request->match_to;
			$new_match->status = 1;
			$new_match->action_user_id = Auth::id();
			$new_match->save();
			$data = null;
			$user = User::find($request->match_to);
			$login_user = User::find(Auth::id());
			sendPushNotification($user->device_token,$user->device_type,$login_user->name.' has sent you a request to Match.',$login_user->name.' has sent you a request to Match.',1,$request->match_to);
			return $this->sendResponse($data, 'Match request sent succesfully!.');
		} catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.',$e,200);
		}
		return $this->sendError('Something went wrong, Please try again!.',null,200);
    }
}
