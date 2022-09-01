<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\EmailSetting;

class SettingController extends Controller
{
    public function index()
    {
        $email_setting = EmailSetting::latest()->first();
        $setting = Setting::latest()->first();
        return view('admin.settings.index',compact('setting','email_setting'));
    }

    public function email_setting(Request $request,$id)
    {
        $email_setting = EmailSetting::where('id',$id)->first();
        $email_setting->host = $request->host;
        $email_setting->port = $request->port;
        $email_setting->email = $request->email;
        $email_setting->password = $request->password;
        $email_setting->from_address = $request->from_address;
        $email_setting->from_name = $request->from_name;
        $email_setting->encryption = $request->encryption;
        $email_setting->save();
        return redirect()->route('settings.index')->with('message','Email setting updated successfully');

    }
    public function app_setting(Request $request,$id)
    {
        $app_setting = Setting::where('id',$id)->first();
        $app_setting->name = $request->app_name;
        $app_setting->url = $request->app_url;
        $app_setting->env = $request->app_env;
        $app_setting->debug = $request->app_debug;
        $app_setting->api_log = $request->api_log;
        $app_setting->save();
        return redirect()->route('settings.index')->with('message','General setting updated successfully');

    }
    public function key_setting(Request $request,$id)
    {
        $app_setting = Setting::where('id',$id)->first();
        $app_setting->fcm = $request->fcm;
        $app_setting->save();
        return redirect()->route('settings.index')->with('message','Key setting updated successfully');

    }
}
