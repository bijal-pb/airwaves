<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\ApiLog;
use DB;



class ApiLogsController extends Controller
{
    public static function log($request){

        if(!self::isJson($request)){
            $input = json_encode($request);
        }        
        
        // $api_log = new ApiLog;
        // $api_log->method = $request->method();
        // $api_log->url = $request->path();
        // $api_log->response = $request->getContent();
        // $api_log->ip = $request->ip();
        // $api_log->save();
        $api_log = [
			"method" => $request->method(),
			"url" => $request->path(),
            "response" => $request->getContent(),
            "ip" => $request->ip(),
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];

    	$res_insert = DB::table("api_logs")
    	->insert($api_log);
    }

    public static function isJson($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
     }

}
