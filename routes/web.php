<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PrivacyTermController;
use App\Http\Controllers\TermCondiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('front.welcome');
// });
Route::get('/', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return redirect('/');
});

Route::get('privacy-policy',[ PrivacyTermController::class ,'privacy']);
Route::get('term-condition',[ PrivacyTermController::class ,'term']);
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
Route::group(['prefix'=> 'admin','middleware' => ['auth', 'verified']], function () {
    Route::resource('roles', RoleController::class);
});
Route::group(['prefix'=> 'admin','middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::view('profile', 'profile.show');
    Route::resource('users', UserController::class);
    Route::get('notification', [ NotificationController::class ,'index'])->name('notifications.index');
    Route::post('send_notifications', [ NotificationController::class ,'send_notifications'])->name('notifications.send');
    Route::get('setting', [ SettingController::class ,'index'])->name('settings.index');
    Route::patch('email_setting/{id}', [ SettingController::class ,'email_setting'])->name('email.setting.update');
    Route::patch('app_setting/{id}', [ SettingController::class ,'app_setting'])->name('app.setting.update');
    Route::patch('key_setting/{id}', [ SettingController::class ,'key_setting'])->name('key.setting.update');
    Route::get('apilogs',[LogController::class,'logs'])->name('apilog.index');
    Route::get('delete_logs',[LogController::class,'delete_all_log'])->name('apilog.delete');
    Route::get('privacy',[PrivacyTermController::class,'index'])->name('privacy.index');
    Route::post('privacy',[PrivacyTermController::class,'update'])->name('privacy.update');
    Route::get('posts',[PostController::class,'index'])->name('post.index');
    Route::get('detail/{id}',[PostController::class,'detail'])->name('post.detail');
    Route::get('event',[EventController::class,'index'])->name('event.index');
    Route::get('event-detail/{id}',[EventController::class,'detail'])->name('event.detail');

    Route::get('group',[GroupController::class,'index'])->name('group.index');
    Route::get('group-detail/{id}',[GroupController::class,'detail'])->name('group.detail');
});
