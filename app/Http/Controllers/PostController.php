<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Yajra\Datatables\Datatables;
use DB;

class PostController extends Controller
{
	public function index(){
		return view('eventlist');
	}
    public function get_post()
    {
        // return Datatables::of(Post::query())->make(true);

        $post = DB::table('posts')
        		->join('users', 'users.id', '=', 'posts.posted_by')
        		->join('comments', 'comments.post_id', '=', 'posts.id')
        		->select('posts.id', 'posts.title', 'posts.schedule_date', 'users.username', DB::raw('count(comments.id) as comments_count'))
        		->groupBy('posts.id')
        		->groupBy('users.username');

        return Datatables::of($post)->make(true);
    }
}
