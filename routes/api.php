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

//post service
// Route::get('post', 'WebServices\PostService@index');
Route::get('post/get', 'WebServices\PostService@get_post');
Route::get('post/network_post', 'WebServices\PostService@get_network_post');
Route::get('post/my_post', 'WebServices\PostService@get_my_post');
Route::post('post/add', 'WebServices\PostService@store');
Route::post('post/edit', 'WebServices\PostService@update');
Route::post('post/delete', 'WebServices\PostService@delete');

//comment service
Route::get('comment/get', 'WebServices\CommentService@get_comment');
Route::get('comment/post_comment', 'WebServices\CommentService@get_post_comment');
Route::get('comment/comment_child', 'WebServices\CommentService@get_comment_child');

Route::post('comment/add', 'WebServices\CommentService@store');
Route::post('comment/edit', 'WebServices\CommentService@update');
Route::post('comment/delete', 'WebServices\CommentService@delete');

//do like service
Route::post('like', 'WebServices\LikeService@store');
Route::post('unlike', 'WebServices\LikeService@delete');

//do follow service
Route::get('follow/get', 'WebServices\FollowService@index');
Route::post('follow', 'WebServices\FollowService@store');
Route::post('unfollow', 'WebServices\FollowService@delete');

//user service

Route::get('user/get','WebServices\UserService@get_user_list');
Route::get('user/search','WebServices\UserService@searchUser');
Route::post('user/test','WebServices\UserService@testUser');
Route::post('user/sign_up','WebServices\UserService@signUp');
Route::post('user/sign_in','WebServices\UserService@signIn');
Route::post('user/edit_user_profile','WebServices\UserService@editUserProfile');
Route::post('user/get_user_detail','WebServices\UserService@getUserDetail');
Route::post('user/sign_out','WebServices\UserService@signOut');

/*Upload Picture*/
Route::post('user/upload_picture','WebServices\UserService@uploadPicture');


// initial data
Route::post('user/initial_data','WebServices\UserService@initialData');
Route::post('testSearchy','WebServices\UserService@testSearchy');