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
// Route::get('post', 'WebServices\PostService@index');
Route::get('post/get', 'WebServices\PostService@get_post');
Route::post('post/get', 'WebServices\PostService@get_post');
Route::post('post/network_post', 'WebServices\PostService@get_network_post');
Route::post('post/my_post', 'WebServices\PostService@get_my_post');
Route::post('post/add', 'WebServices\PostService@store');
Route::post('post/edit', 'WebServices\PostService@update');
Route::post('post/delete', 'WebServices\PostService@delete');

//comment service
Route::get('comment/get', 'WebServices\CommentService@get_comment');
Route::get('comment/post_comment', 'WebServices\CommentService@get_post_comment');
Route::post('comment/add', 'WebServices\CommentService@store');
Route::post('comment/edit', 'WebServices\CommentService@update');
Route::post('comment/delete', 'WebServices\CommentService@delete');

//do like service
Route::post('like', 'WebServices\LikeService@store');
Route::post('unlike', 'WebServices\LikeService@delete');

//user service
// Route::get('user', 'WebServices\UserService@index');
// Route::get('user/{post}', 'WebServices\UserService@show');

Route::post('user/test','WebServices\UserService@testUser');
Route::post('user/sign_up','WebServices\UserService@signUp');
Route::post('user/sign_in','WebServices\UserService@signIn');
Route::post('user/edit_user_profile','WebServices\UserService@editUserProfile');
Route::post('user/get_user','WebServices\UserService@getUser');
Route::post('user/sign_out','WebServices\UserService@signOut');

// initial data
Route::post('user/initial_data','WebServices\UserService@initialData');