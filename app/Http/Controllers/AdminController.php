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

	public function event_create(){
		return view('eventCreate');
	}

    public function event_store(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $schedule_date = $request->schedule_date;
        $posted_by = $request->posted_by;

        $post = new Post();
        $post->title = $title;
        $post->content = $content;
        $post->schedule_date = Date('Y-m-d h:i:s',strtotime($schedule_date));
        $posted_by != null ? $post->posted_by = $posted_by : $post->posted_by = Auth::id();
        // $post->posted_by = Auth::id();

        $post_image = $request->file('post_image');
        $path = Storage::disk('public')->put('posts', $post_image);
        if($path){
            $post->original_image_url = "storage/app/public/" . $path;
        }

        $post->save();

        return $this->createSuccessMessage($post);

        // $post = Post::create($request->all());
        // return response()->json($post, 201);
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

		return view('userDet', 
			[
				'user_data' => User::getUserDetail($request->user_id)[0],
				'event_data' => Post::get_user_post(null, null, 100, $request->user_id)
			]
		);
	}

	public function user_edit(Request $request){
		return view('userEdit', 
			[
				'user_data' => User::getUserDetail($request->user_id)[0]
			]
		);
	}
}
