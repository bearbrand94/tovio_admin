<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'posted_by', 'schedule_date', 'is_completed'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function search_tag($keyword, $paginate=10, $page=1){
        $post_data = DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select(DB::raw("'posts' as table_name"), 'posts.id as reference_id', 'posts.content', 'posts.posted_by as user_id', 'users.first_name as posted_by_name', 'users.username', 'posts.created_at')
                    ->where('content', 'like', '%#' . $keyword . '%');

        $comment_data = DB::table('comments')
                    ->join('users', 'users.id', '=', 'comments.commented_by')
                    ->select(DB::raw("'comments' as table_name"), 'comments.id as reference_id', 'comments.content', 'comments.commented_by as user_id', 'users.first_name as posted_by_name', 'users.username', 'comments.created_at')
                    ->where('content', 'like', '%#' . $keyword . '%')
                    ->union($post_data)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $slice = array_slice($comment_data->toArray(), $paginate * ($page - 1), $paginate);
        $result = new LengthAwarePaginator($slice, count($comment_data), $paginate);
        return $result;
    }

    public static function search_post($keyword, $paginate=10, $page=1){
        $post = DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
            ->select('posts.*', 'users.first_name as posted_by_name', 'users.username')
            ->where('title', 'like', '%' . $keyword . '%')
            ->orWhere('first_name', 'like', '%' . $keyword . '%')
            ->orWhere('username', 'like', '%' . $keyword . '%')
            ->get();

        $slice = array_slice($post->toArray(), $paginate * ($page - 1), $paginate);
        $result = new LengthAwarePaginator($slice, count($post), $paginate);

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $result;
    }

    public static function get_post($date_start = null, $date_end = null, $page_show = 10){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name', 'users.username');
        if($date_start){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $post = $post->where('schedule_date', '>', $date_start);
        }
        if($date_end){
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '<', $date_end);
        }

        $post = $post->orderBy('schedule_date', 'asc');
        $post = $post->paginate($page_show);
        $post->appends(['date_start' => $date_start, 'date_end' => $date_end])->links();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }

    public static function get_user_post($date_start = null, $date_end = null, $page_show = 10, $user_id){
        $post =  DB::table('posts')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->select('posts.*', 'users.first_name as posted_by_name', 'users.username')->where('posts.posted_by', $user_id);
        if($date_start && $date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->orderBy('schedule_date', 'desc');
        $post = $post->paginate($page_show);
        $post->appends(['date_start' => $date_start, 'date_end' => $date_end])->links();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', $user_id)->where('table_name', 'posts')->count();    
        }
        return $post;
    }

    public static function get_network_post($date_start = null, $date_end = null, $page_show = 10){
        $post =  DB::table('networks')
                    ->join('posts', 'networks.following_id', '=', 'posts.posted_by')
                    ->join('users', 'users.id', '=', 'posts.posted_by')
                    ->where('networks.follower_id', '=',  Auth::id())
                    ->select('posts.*', 'users.first_name as posted_by_name', 'users.username');
        if($date_start && $date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->orderBy('schedule_date', 'desc');
        $post = $post->paginate($page_show);
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
                    ->select('posts.*', 'users.first_name as posted_by_name', 'users.username', 'users.original_image_url as user_image_url');
        $post = $post->where('posts.id', $post_id)->get();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]->comment_count = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]->post_like_count = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
            $post[$i]->post_liked_by_me = Like::where('reference_id', $post[$i]->id)->where('user_id', Auth::id())->where('table_name', 'posts')->count();    
        }
        return $post;
    }
}
