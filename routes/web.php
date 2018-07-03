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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/hello',function(){
//     return 'Hello World!';
// });

Route::get('hello', 'Hello@index');
Route::get('blade', function () {
    $drinks = array('Vodka','Gin','Brandy');
    return view('page',array('name' => 'The Raven','day' => 'Friday','drinks' => $drinks));
});

Route::get('/hello/{name}', 'Hello@show');
Route::get('admin', function () {
    return view('admin_template');
});

Route::get('/','Front@index');
Route::get('/products','Front@products');
Route::get('/products/details/{id}','Front@product_details');
Route::get('/products/categories','Front@product_categories');
Route::get('/products/brands','Front@product_brands');
Route::get('/blog','Front@blog');
Route::get('/blog/post/{id}','Front@blog_post');
Route::get('/contact-us','Front@contact_us');
Route::get('/login','Front@login');
Route::get('/logout','Front@logout');
Route::get('/cart','Front@cart');
Route::get('/checkout','Front@checkout');
Route::get('/search/{query}','Front@search');

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
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
