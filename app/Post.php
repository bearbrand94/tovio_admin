<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'posted_by', 'schedule_date', 'is_completed'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function get_post($date_start = null, $date_end = null){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name');
        if($date_start && $date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->orderBy('schedule_date', 'desc');
        $post = $post->paginate(5);
        $post->appends(['date_start' => $date_start, 'date_end' => $date_end])->links();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }

    public static function get_my_post($date_start = null, $date_end = null){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name')->where('posted_by', Auth::id());
        if($date_start && $date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->orderBy('schedule_date', 'desc');
        $post = $post->paginate(5);
        $post->appends(['date_start' => $date_start, 'date_end' => $date_end])->links();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }

    public static function get_network_post($date_start = null, $date_end = null){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name');
        if($date_start && $date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->orderBy('schedule_date', 'desc');
        $post = $post->paginate(5);
        $post->appends(['date_start' => $date_start, 'date_end' => $date_end])->links();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }

    public static function get_post_by_id($post_id){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name');
        $post = $post->where('posts.id', $post_id)->get();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }
}
