<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
* @OA\Info(
*      description="",
*     version="1.0.0",
*      title="Airwave",
* )
**/
 
/**
*  @OA\SecurityScheme(
*     securityScheme="bearer_token",
*         type="http",
*         scheme="bearer",
*     ),
**/
class AuthController extends BaseController
{
    /**
    *  @OA\Post(
    *     path="/api/register",
    *     tags={"Register"},
    *     summary="Register",
    *     operationId="register",
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
    *         name="password",
    *         in="query",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="phone",
    *         in="query",
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
   
   public function register(Request $request)
   {
       $validator = Validator::make($request->all(),[
           'first_name' => 'required',
           'last_name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:8',
           'gender' => 'required|in:1,2',
           'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'

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
       $user = new User();
       $user->name = $request->first_name. " ". $request->last_name;
       $user->first_name = $request->first_name;
       $user->last_name = $request->last_name;
       $user->email = $request->email;
       $user->password = bcrypt($request->password);
       $user->phone = $request->phone;
       $user->device_type = $request->device_type;
       $user->device_id = $request->device_id;
       $user->device_token = $request->device_token;
       $user->gender = $request->gender;
       $user->bio = $request->bio;
       $user->photo = $filename;
       $user->save();
       $user->assignRole([2]);
       $tokenResult = $user->createToken('authToken')->plainTextToken;
       $data['token'] = $tokenResult;
       $data['user'] =  new UserResource($user);
       return $this->sendResponse($data, 'User register successfully.');
   }
   /**
   *  @OA\Post(
   *     path="/api/login",
   *     tags={"Login"},
   *     summary="Login",
   *     operationId="login",
   * 
   *     @OA\Parameter(
   *         name="email",
   *         in="query",
   *         @OA\Schema(
   *             type="string"
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
   *         name="password",
   *         in="query",
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ), 
   *     @OA\Parameter(
   *         name="social_type",
   *         in="query",
   *         description="google | mac | spotify",   
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ), 
   *     @OA\Parameter(
   *         name="social_id",
   *         in="query",
   *         @OA\Schema(
   *             type="string"
   *         )
   *     ),  
   * 
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
  
   public function login(Request $request)
   {
       $validator = Validator::make($request->all(),[
           'social_type' => 'nullable|in:google,mac,spotify',
           'email' => 'nullable|required_if:social_type,null,google,mac|email',
           'password' => 'required_if:social_type,null',
           'social_id' => 'required_if:social_type,google,mac,spotify',
       ],
        [
            'email.required_if' => 'Email is required',
            'password.required_if' => 'Password is required, if not login with social.',
            'social_id.required_if' => 'Social id is required.'
        ]);

       if($validator->fails())
       {
           return $this->sendError($validator->messages()->first(),null,200);
       }
       if($request->social_type == null)
       {
            $credentials = request(['email','password']);
            if(!Auth::attempt($credentials))
            {
                return $this->sendError('Please enter valid email or password!.','',200);
            }
            $user = User::where('email', $request->email)->first();
            $user->device_type = $request->device_type;
            $user->device_id = $request->device_id;
            $user->device_token = $request->device_token;
            $user->save();
            $user->tokens()->delete();
            if($user->status == 2){
                return $this->sendError('Your account is blocked, Please contact administrator!','',200);
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $data['token'] = $tokenResult;
            $data['user'] =  new UserResource($user);
            return $this->sendResponse($data, 'User login successfully!');
       } else {
            $user = User::where('email', $request->email)->first();
            if($user)
            {
                $user->device_type = $request->device_type;
                $user->device_id = $request->device_id;
                $user->device_token = $request->device_token;
                $user->social_type = $request->social_type;
                $user->social_id = $request->social_id;
                $user->save();
                $user->tokens()->delete();
                if($user->status == 2){
                    return $this->sendError('Your account is blocked, Please contact administrator!','',200);
                }
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $data['token'] = $tokenResult;
                $data['user'] =  new UserResource($user);
                return $this->sendResponse($data, 'User login successfully!');
            } else {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->social_type = $request->social_type;
                $user->social_id = $request->social_id;
                $user->device_type = $request->device_type;
                $user->device_id = $request->device_id;
                $user->device_token = $request->device_token;
                $user->save();
                $user->assignRole([2]);
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $data['token'] = $tokenResult;
                $data['user'] =  new UserResource($user);
                return $this->sendResponse($data, 'User login successfully!');
            }

       }
       return $this->sendError('This user does not exist.','',200);
    
   }
   /**
   *  @OA\Get(
   *     path="/api/logout",
   *     tags={"Logout"},
   *     summary="Logout",
   *     security={{"bearer_token":{}}},
   *     operationId="logout",
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
   public function logout()
   {
       Auth::user()->currentAccessToken()->delete();
       $data = null;
       return $this->sendResponse($data, 'User logout successfully!');
   }
   
}
