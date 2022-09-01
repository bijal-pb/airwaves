<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{

    public function index()
    {
        return view('admin.notifications.send');
    }

    public function send_notifications(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required'
        ]);
        if($request->device == 'all')
        {
            $users = User::where('device_id','!=','null')->get();
        }
        else
        {
            $users = User::where('device_id','!=','null')->where('device_type',$request->device)->get();
        }
        foreach($users as $user)
        {
            $badge = Notification::where('user_id',$user->id)->where('status',1)->count();   
            $badge += 1;
            sendPushNotification($user->device_token,$user->device_type,$request->title,$request->message, $badge,$user->id);
        }
        return redirect()->back()->with('success','Notificaion sent successfully!');
    }
}
