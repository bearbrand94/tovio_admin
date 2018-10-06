<?php

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

Route::get('/',function(){
    return view("auth/login");
});

// Route::get('/hello',function(){
//     return 'Hello World!';
// });


//user service
// Route::get('user', 'WebServices\UserService@index');
// Route::get('user/{post}', 'WebServices\UserService@show');

// Route::post('user/test','WebServices\UserService@testUser');
// Route::post('user/sign_up','WebServices\UserService@signUp');
// Route::post('user/sign_in','WebServices\UserService@signIn');
// Route::post('user/edit_user_profile','WebServices\UserService@editUserProfile');
// Route::post('user/get_user','WebServices\UserService@getUser');
// Route::post('user/sign_out','WebServices\UserService@signOut');
// // Route::post('/set_lbod','WebServices\UserService@setLBOD');

// // initial data
// Route::post('initial_data','WebServices\UserService@initialData');
Route::group(['middleware' => 'web'], function () {
	Auth::routes();

	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/admin/home', 'HomeController@index');
	Route::get('/admin/events', 'AdminController@event');
	Route::get('/admin/event/list', 'AdminController@event');
	Route::get('/admin/event/detail', 'AdminController@event_detail');
	Route::get('/admin/event/create', 'AdminController@event_create');
	Route::post('admin/event/add', 'AdminController@event_store');

	Route::get('/admin/users', 'AdminController@user');
	Route::get('/admin/user/detail', 'AdminController@user_detail');
	Route::get('/admin/user/edit', 'AdminController@user_edit');

	Route::get('/event/list', 'PostController@get_post');
	Route::get('/user/list', 'UserController@get_user');
});