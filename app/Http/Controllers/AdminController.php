<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;

class AdminController extends Controller
{
	public function index(){
		return view('home');
	}

	public function event(){
		return view('event_list');
	}

	public function event_detail(Request $request){
		$event_data = Post::get_post_by_id($request->post_id);
		$comment_data = Comment::get_post_comment($request->post_id);
		return view('event_detail', 
			[
				'event_data' => $event_data,
				'comment_data' => $comment_data
			]
		);
	}

	public function user(){
		return view('user');
	}

	public function user_detail(Request $request){
		return view('user_detail');
	}
}
