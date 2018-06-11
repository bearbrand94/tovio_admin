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
Route::post('post', 'WebServices\PostService@store');
Route::put('post/{post}', 'WebServices\PostService@update');
Route::delete('post/{post}', 'WebServices\PostService@delete');