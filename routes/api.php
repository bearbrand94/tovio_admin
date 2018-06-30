<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return "This text is returned from API";
});
Route::get('/get_user','WebServices\UserService@get_user');

//post service
Route::get('post', 'WebServices\PostService@index');
Route::get('post/{post}', 'WebServices\PostService@show');
Route::post('user_post', 'WebServices\PostService@get_user_post');
Route::post('post', 'WebServices\PostService@store');
Route::put('post/{post}', 'WebServices\PostService@update');
Route::delete('post/{post}', 'WebServices\PostService@delete');

//comment service
Route::get('post/{post}/comment', 'WebServices\CommentService@get_post_comment');
Route::post('post/{post}/comment', 'WebServices\CommentService@store');
Route::put('comment/{post}', 'WebServices\CommentService@update');
Route::delete('comment/{post}', 'WebServices\CommentService@delete');

//do like service
Route::post('like', 'WebServices\LikeService@store');
Route::put('like/{post}', 'WebServices\LikeService@update');
Route::delete('like', 'WebServices\LikeService@delete');

//user service
// Route::get('user', 'WebServices\UserService@index');
// Route::get('user/{post}', 'WebServices\UserService@show');

Route::post('user/test','WebServices\UserService@testUser');
Route::post('user/sign_up','WebServices\UserService@signUp');
Route::post('user/sign_in','WebServices\UserService@signIn');
Route::post('user/edit_user_profile','WebServices\UserService@editUserProfile');
Route::post('user/get_user','WebServices\UserService@getUser');
Route::post('user/sign_out','WebServices\UserService@signOut');
// Route::post('/set_lbod','WebServices\UserService@setLBOD');

// initial data
Route::post('initial_data','WebServices\UserService@initialData');