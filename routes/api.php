<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PrivacyTermController;
use App\Http\Controllers\Api\PeopleController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\GenresController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\EventController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::middleware('apilogs')->post('register', [AuthController::class,'register']);
    Route::middleware('apilogs')->post('login', [AuthController::class,'login']);
    Route::middleware('apilogs')->post('forgot-password', [UserController::class,'forgot_password']);

    Route::group(['middleware' => ['auth:sanctum', 'verified', 'apilogs']], function () {
        Route::post('edit-profile',[UserController::class, 'edit_profile']);
        Route::post('change-location',[UserController::class, 'change_location']);
        Route::post('change-password',[UserController::class, 'change_password']);
        Route::post('refresh-token', [UserController::class, 'refresh_token']);
        
        Route::get('home',[PostController::class,'home']);

        Route::post('online',[UserController::class, 'online']);
        Route::post('add-track',[UserController::class, 'add_track']);  
        Route::post('delete-track',[UserController::class, 'delete_track']);
        Route::get('get-tracks',[UserController::class, 'get_tracks']);
        
        Route::get('explore',[PeopleController::class,'explore']);
        Route::get('match',[PeopleController::class,'match']);
        Route::post('match-request',[PeopleController::class,'match_request']);

        Route::post('create-post',[PostController::class,'create_post']);
        Route::get('get-post',[PostController::class,'get_post']);
        Route::post('get-post',[PostController::class,'get_post_by_id']);
        Route::get('user-post',[PostController::class,'user_post']);
        Route::post('post-like',[PostController::class,'post_like']);
        Route::post('post-comment',[PostController::class,'post_comment']);
        Route::post('post-save',[PostController::class,'post_save']);
        Route::post('get-save-post',[PostController::class,'get_save_post']);
        Route::post('delete-post',[PostController::class,'delete_post']);
        Route::post('report-post',[PostController::class,'report_post']);
        
        Route::get('get-genres',[GenresController::class,'get_genres']);

        Route::post('create-group',[GroupController::class,'create_group']);
        Route::get('get-group',[GroupController::class,'get_group']);
        Route::post('delete-group',[GroupController::class,'delete_group']);
        Route::post('edit-group',[GroupController::class, 'edit_group']);

        Route::post('group/event-create/enable', [GroupController::class, 'event_create_enable']);
        Route::post('join-group',[GroupController::class,'join_group']);
        Route::post('exit-group',[GroupController::class, 'exit_group']);
        
        Route::post('get-group-requests',[GroupController::class, 'get_group_requests']);
        Route::post('accept-group-request',[GroupController::class, 'accept_group_request']);


        Route::get('get-group-detail',[GroupController::class,'get_group_detail']);
        
        Route::post('create-event',[EventController::class,'create_event']);
        Route::get('get-event',[EventController::class,'get_event']);
        Route::post('delete-event',[EventController::class,'delete_event']);
        Route::post('edit-event',[EventController::class, 'edit_event']);

        Route::get('meetups',[GroupController::class, 'meetups']);

        Route::post('notification/enable', [UserController::class, 'enable_notification']);

        Route::post('report', [NotificationController::class,'report_mail']);
        Route::get('notifications', [NotificationController::class,'notifications']);
        Route::get('read-notifications', [NotificationController::class,'read_notifications']);
        Route::get('logout', [AuthController::class,'logout']);
    });
   
    