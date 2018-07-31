<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Comment;

class AdminController extends Controller
{
	public function index(){
		return view('home');
	}

	public function event(){
		return view('eventList');
	}

	public function event_detail(Request $request){
		$event_data = Post::get_post_by_id($request->post_id);
		$comment_data = Comment::get_post_comment($request->post_id, 100);
		for ($i=0; $i < count($comment_data) ; $i++) { 
			$comment_data[$i]->child = Comment::get_comment_child($comment_data[$i]->id, 100);
		}
		return view('eventDetail', 
			[
				'event_data' => $event_data[0],
				'comment_data' => $comment_data
			]
		);
	}

	public function user(){
		return view('userList');
	}

	public function user_detail(Request $request){
		// $event_data = Post::get_post_by_id($request->post_id);
		// $comment_data = Comment::get_post_comment($request->post_id, 100);
		// for ($i=0; $i < count($comment_data) ; $i++) { 
		// 	$comment_data[$i]->child = Comment::get_comment_child($comment_data[$i]->id, 100);
		// }

		return view('userDetail', 
			[
				'user_data' => User::getUserDetail($request->user_id)[0],
				'event_data' => Post::get_user_post(null, null, 100, $request->user_id)
			]
		);
	}
}
