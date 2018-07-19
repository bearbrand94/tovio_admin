<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    protected $fillable = ['post_id', 'content', 'commented_by', 'comment_date'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function get_post_comment($post_id, $page_show = 10){
        $comment = DB::table('comments')
                    ->join('users', 'users.id', '=', 'comments.commented_by')
                    ->where('post_id', $post_id)
                    ->select('comments.*', 'users.first_name as commented_by_name');
        $comment = $comment->paginate($page_show);
        $comment->appends(['post_id' => $post_id])->links();
                
        for ($i=0; $i < count($comment); $i++) { 
            $comment[$i]->comment_like_count = Like::where('reference_id', $comment[$i]->id)->where('table_name', 'comments')->count();
            $comment[$i]->comment_like_count = Like::where('reference_id', $comment[$i]->id)->where('user_id', Auth::id())->where('table_name', 'comments')->count();    
        }
        return $comment;
    }

    public static function get_network_comment($post_id){
        $comment = DB::table('comments')
                    ->join('users', 'users.id', '=', 'comments.commented_by')
                    ->where('post_id', $post_id)
                    ->select('comments.*', 'users.first_name as commented_by_name')
                    ->get();

        for ($i=0; $i < count($comment); $i++) { 
            $comment[$i]->comment_like_count = Like::where('reference_id', $comment[$i]->id)->where('table_name', 'comments')->count();
            $comment[$i]->comment_liked_by_me = Like::where('reference_id', $comment[$i]->id)->where('user_id', Auth::id())->where('table_name', 'comments')->count();    
        }
        return $comment;
    }
}
