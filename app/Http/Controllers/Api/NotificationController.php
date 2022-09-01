<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Auth;
use Mail;


class NotificationController extends Controller
{
    /**
    *  @OA\Get(
    *     path="/api/notifications",
    *     tags={"Notifications"},
    *     summary="Get Notifications",
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
   public function notifications()
   {
       $notification = Notification::where('user_id',Auth::id())->orderBy('id','desc')->get();
       $unread_count = Notification::where('user_id',Auth::id())
                                   ->where('status',1)->count();
       return response()->json([
           'data' =>  $notification,
           'unread' => $unread_count,
           'status_code' => 200,
       ],200);
   }

   /**
   *  @OA\Get(
   *     path="/api/read_notifications",
   *     tags={"Read Notification"},
   *     summary="Change status of notification.",
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
   public function read_notifications()
   {
       $notifications = Notification::where('user_id',Auth::id())
                       ->where('status',1)
                       ->get();
       foreach($notifications as $noti)
       {
           $notification = Notification::find($noti->id);
           $notification->status = 2;
           $notification->save();
       }
       return response()->json([
           'message' => 'Read status set successfully!',
           'status_code' => 200,
       ],200);
   }
   /**
	 *  @OA\Post(
	 *     path="/api/report",
	 *     tags={"report"},
     *     security={{"bearer_token":{}}},
	 *     summary="Report mail send to admin",
	 *     operationId="report",
	 * 
	 *     @OA\Parameter(
	 *         name="user_id",
     *         required=true,
	 *         in="query",
	 *         @OA\Schema(
	 *             type="integer"
	 *         )
	 *     ),  
     *     @OA\Parameter(
	 *         name="reason",
     *         required=true,
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
   public function report_mail(Request $request)
   {
       try{

            $user = User::find(Auth::id());
            $report_to = User::find($request->user_id);
            $email = 'yaxu.ingeniousmindslab@gmail.com';

            $data = [
                'username' => $user->name,
                'report_to' => $report_to->name,
                'reason' => $request->reason,
            ];
            Mail::send('mail.report', $data, function($message) use ($email) {
                $message->to($email, 'Airwaves')->subject
                ('Report');
            });
            $success = null;
            return response()->json([
                'message' => 'Report is sent to admin successfully.',
                'status_code' => 200,
            ],200);
       }catch(Exception $e){
            return $this->sendError('Something went wrong, Please try again!.', $e->getMessage(),200);
        }
    }
}
