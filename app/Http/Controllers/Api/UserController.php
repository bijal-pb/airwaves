<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTrack;
use Hash;
use Mail;


class UserController extends BaseController
{
	/**
    *  @OA\Post(
    *     path="/api/edit-profile",
    *     tags={"Edit Profile"},
	*     summary="Edit profile",
	*     security={{"bearer_token":{}}},
    *     operationId="edit-profile",
    *     
    *     @OA\Parameter(
    *         name="first_name",
    *         in="query",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    
    *   @OA\Parameter(
    *         name="last_name",
    *         in="query",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),   
    *   @OA\Parameter(
    *         name="gender",
    *         in="query",
    *         description="1-Male | 2-Female",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),   
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),    
    *     @OA\Parameter(
    *         name="bio",
    *         in="query",
    *         @OA\Schema(
    *             type="text"
    *         )
    *     ),
    *    @OA\RequestBody(
    *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="photo",
    *                      description="photo",
    *                      type="file",
    *                      @OA\Items(type="string", format="binary")
    *                   ),
    *               ),
    *           ),
    *       ),
    *     @OA\Parameter(
    *         name="device_type",
    *         in="query",
    *         description="android | ios",
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="device_id",
    *         in="query",
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="device_token",
    *         in="query",
    *         @OA\Schema(
    *             type="string"
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
	public function edit_profile(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email|unique:users,email,'.Auth::id(),
			'gender' => 'required|in:1,2',
			'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:100000'
		]);
 
		if($validator->fails())
		{
		 return $this->sendError($validator->messages()->first(),null,200);
		}
		$filename = null;
		if($request->hasfile('photo')) {
		 $file = $request->file('photo');
		 $filename = time().$file->getClientOriginalName();
		 $file->move(public_path().'/uploads/', $filename);  
		}
		$user = User::find(Auth::id());
		$user->name = $request->first_name. " ". $request->last_name;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->device_type = $request->device_type;
		$user->device_id = $request->device_id;
		$user->device_token = $request->device_token;
		$user->gender = $request->gender;
		$user->bio = $request->bio;
		if($request->hasfile('photo'))
		{
			$user->photo = $filename;
		}
		$user->save();
		$user =  User::with('tracks')->find(Auth::id());
		$data = new UserResource($user);
		return $this->sendResponse($data, 'User profile updated successfully.');
	}

	/**
	 *  @OA\Post(
	 *     path="/api/change-password",
	 *     tags={"change password"},
	 *     summary="Change password",
	 *     security={{"bearer_token":{}}},
	 *     operationId="change-password",
	 * 
	 *     @OA\Parameter(
	 *         name="old_password",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="new_password",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),    
	 *     @OA\Parameter(
	 *         name="password_confirmation",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
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

	public function change_password(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'old_password' => 'required',
			'new_password' => 'required|min:8|same:password_confirmation',
		]);

		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		$user = User::find(Auth::id());
		if(Hash::check($request->old_password,$user->password)) {
			$user->password = bcrypt($request->new_password);
			$user->save();
			$data = null;
			return $this->sendResponse($data, 'Password changed successfully!.');
		}
		return $this->sendError('old password incorrect!','',200);

	}

	/**
	 *  @OA\Post(
	 *     path="/api/forgot-password",
	 *     tags={"forgot password"},
	 *     summary="Forgot password",
	 *     operationId="forgot-password",
	 * 
	 *     @OA\Parameter(
	 *         name="email",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
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
	public function forgot_password(Request $request)
	{
		
		$validator = Validator::make($request->all(),[
			'email' => 'required|email|exists:users,email',
		]);

		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		$user = User::where('email',$request->email)->first();
        if(empty($user))
        {
            return $this->sendError('This email not registered'); 
        }

        try{
            $newPass = substr(md5(time()), 0, 10);
            $user->password = bcrypt($newPass);
            $user->save();
            $data = [
                'username' => $user->name,
                'password' => $newPass
            ];
            $email = $user->email;
            Mail::send('mail.forgot', $data, function($message) use ($email) {
                $message->to($email, 'test')->subject
                   ('Forgot Password');
            });
            $success = null;
            return $this->sendResponse($success, 'Email sent succesfully!');

        } catch (Exception $e)
        {
            return $this->sendError('Something went wrong, Please try again!.', $e,200);
        }      
		
	}
	/**
	 *  @OA\Post(
	 *     path="/api/change-location",
	 *     tags={"change location"},
	 *     summary="Change Location",
	 *     security={{"bearer_token":{}}},
	 *     operationId="change-location",
	 * 
	 *     @OA\Parameter(
	 *         name="latitude",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),   
	 *     @OA\Parameter(
	 *         name="longitude",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="address",
	 *         in="query",
	 *         @OA\Schema(
	 *             type="text"
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
	public function change_location(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'latitude' => 'required',
			'longitude' => 'required',
		]);

		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$user = User::find(Auth::id());
			$user->latitude = $request->latitude;
			$user->longitude = $request->longitude;
			$user->address = $request->address;
			$user->save();
			$data = null;
            return $this->sendResponse($data, 'Update location successfully!');
		} catch (Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	} 

	/**
	 *  @OA\Post(
	 *     path="/api/online",
	 *     tags={"change online status"},
	 *     summary="Change Online status",
	 *     security={{"bearer_token":{}}},
	 *     operationId="change-location",
	 * 
	 *     @OA\Parameter(
	 *         name="online",
	 *         in="query",
	 * 	 	   description="1-Online | 2-Offline",				
	 *         required=true,
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
	public function online(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'online' => 'required | in:1,2',
		]);

		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$user = User::find(Auth::id());
			$user->online = $request->online;
			$user->save();
			$data = new UserResource($user);
			if($request->online == 1)
			{
				return $this->sendResponse($data, 'User online successfully!');	
			} else {
            	return $this->sendResponse($data, 'User offline successfully!');
			}
		} catch (Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	/**
	 *  @OA\Post(
	 *     path="/api/add-track",
	 *     tags={"Add track user"},
	 *     summary="add track",
	 *     security={{"bearer_token":{}}},
	 *     operationId="add-track",
	 * 
	 * 	   @OA\Parameter(
	 *         name="track_id",
	 *         in="query",
	 * 	 	   description="track_id",				
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
	 *         )
	 *     ),   
	 *     @OA\Parameter(
	 *         name="track",
	 *         in="query",
	 * 	 	   description="track",				
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
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
	public function add_track(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'track' => 'required',
			'track_id' => 'required'
		]);

		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$check_exist = UserTrack::where('track_id',$request->track_id)->where('user_id',Auth::id())->first();
			if($check_exist)
			{
				return $this->sendResponse($check_exist, 'Track already added!');
			}
			$ut = new UserTrack;
			$ut->user_id = Auth::id();
			$ut->track_id = $request->track_id;
			$ut->track = $request->track;
			$ut->save();
			return $this->sendResponse($ut, 'User track added succesfully!');	
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	/**
	 *  @OA\Post(
	 *     path="/api/delete-track",
	 *     tags={"delete track"},
	 *     summary="delete track",
	 *     security={{"bearer_token":{}}},
	 *     operationId="delete-track",
	 * 
	 *     @OA\Parameter(
	 *         name="user_track_id",
	 *         in="query",
	 * 	 	   description="user track id",				
	 *         required=true,
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
	public function delete_track(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'user_track_id' => 'required',
		]);
		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$ut = UserTrack::find($request->user_track_id);
			if($ut){
				$ut->delete();
				return $this->sendResponse('', 'User track deleted succesfully!');
			}
			return $this->sendError('enter valid user_track_id',null,200);	
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	/**
	 *  @OA\Get(
	 *     path="/api/get-tracks",
	 *     tags={"get tracks particular users"},
	 *     summary="get tracks",
	 *     security={{"bearer_token":{}}},
	 *     operationId="get-tracks",
	 * 
	 *     @OA\Parameter(
	 *         name="user_id",
	 *         in="query",
	 * 	 	   description="user id",				
	 *         required=true,
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
	public function get_tracks(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'user_id' => 'required',
		]);
		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$ut = UserTrack::where('user_id',$request->user_id)->get();
			return $this->sendResponse($ut, 'Save tracks list!');
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	/**
	 *  @OA\Post(
	 *     path="/api/refresh-token",
	 *     tags={"refresh token"},
	 *     summary="refresh token",
	 *     security={{"bearer_token":{}}},
	 *     operationId="refresh-token",
	 * 
	 *     @OA\Parameter(
	 *         name="device_token",
	 *         in="query",
	 * 	 	   description="device token",				
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string"
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
	public function refresh_token(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'device_token' => 'required',
		]);
		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$user = User::find(Auth::id());
			$user->device_token = $request->device_token;
			$user->save();
			return $this->sendResponse($user, 'User device token updated successfully!');
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	/**
	 *  @OA\Post(
	 *     path="/api/notification/enable",
	 *     tags={"Notification enable disable"},
	 *     summary="notification enable disable",
	 *     security={{"bearer_token":{}}},
	 *     operationId="notification-enable",
	 * 
	 *     @OA\Parameter(
	 *         name="status",
	 *         in="query",
	 * 	 	   description="1 for enable | 2 for disable",				
	 *         required=true,
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
	public function enable_notification(Request $request){
		$validator = Validator::make($request->all(),[
			'status' => 'required|in:1,2',
		]);
		if($validator->fails())
		{
			return $this->sendError($validator->messages()->first(),null,200);
		}
		try {
			$user = User::find(Auth::id());
			$user->is_notification = $request->status;
			$user->save();
			if($request->status == 1){
				$msg = "Notification enabled successfully!";
			}else{
				$msg = "Notification disabled successfully!";
			}
			return $this->sendResponse($user, $msg);
		}catch(Exception $e)
		{
			return $this->sendError('Something went wrong, Please try again!.', $e,200);
		}
	}
	
}
