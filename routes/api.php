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

function createErrorMessage($message, $errorCode){
	$result = [ "payload"=>'',
			    "error_msg"=>$message,
				"code"=>$errorCode
				];
	return response()->json($result, $errorCode);
}

function createSuccessMessage($payload){
	$result = [ "payload"=>$payload,
			    "error_msg"=>'',
				"code"=>200
				];
	return response()->json($result);
}
	
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return "This text is returned from API";
});

Route::post('test/upload/image', 'WebServices\PostService@upload_picture');

Route::get('test/get/image/', function (Request $request)
{
    return '<img class="img-circle" src="' . URL::to('/storage') . "/". $request->image_url . '" alt="User Avatar">';
});

Route::post('/search', function (Request $request) {
	$page_show = $request->page_show ? $request->page_show : 10;
	$page = $request->page ? $request->page : 1;
	switch (strtolower($request->table)) {
		case 'post':
			$post_data = App\Post::search_post($request->keyword, $page_show, $page);
			for ($i=0; $i < count($post_data); $i++) { 
				$post_data[$i]->user_data = App\User::getUserDetail($post_data[$i]->posted_by);
				$post_data[$i]->follow_data = App\User::getFollowData($post_data[$i]->posted_by);
			}
			return createSuccessMessage($post_data);
			break;
		case 'user':
			$user_data = App\User::search_user($request->keyword, $page_show, $page);
			for ($i=0; $i < count($user_data); $i++) { 
            	$user_data[$i]->network = App\User::get_network($user_data[$i]->id);
            	$user_data[$i]->network_count = count($user_data[$i]->network);
				$user_data[$i]->follow_data = App\User::getFollowData($user_data[$i]->id);
			}
			return createSuccessMessage($user_data);
			break;
		case 'tag':
			$tag_data = App\Post::search_tag($request->keyword, $page_show, $page);
			for ($i=0; $i < count($tag_data); $i++) { 
				$tag_data[$i]->user_data = App\User::getUserDetail($tag_data[$i]->user_id);
				$tag_data[$i]->follow_data = App\User::getFollowData($tag_data[$i]->user_id);
			}
			return createSuccessMessage($tag_data);
			break;
				
		default:
			return "There is no table selected";
			break;
	}
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


//do tag service
Route::post('tag/add', 'WebServices\TagService@store');
Route::post('tag/delete', 'WebServices\TagService@delete');


//user service
Route::get('user/get','WebServices\UserService@get_user_list');
Route::get('user/search','WebServices\UserService@searchUser');
Route::post('user/test','WebServices\UserService@testUser');
Route::post('user/sign_up','WebServices\UserService@signUp');
Route::post('user/sign_in','WebServices\UserService@signIn');
Route::post('user/edit','WebServices\UserService@editUserProfile');
Route::post('user/get_user_detail','WebServices\UserService@getUserDetail');
Route::post('user/sign_out','WebServices\UserService@signOut');

/*Upload Picture*/
Route::post('user/upload_picture','WebServices\UserService@uploadPicture');


// initial data
Route::post('user/initial_data','WebServices\UserService@initialData');
Route::post('testSearchy','WebServices\UserService@testSearchy');