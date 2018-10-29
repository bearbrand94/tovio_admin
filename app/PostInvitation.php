<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class PostInvitation extends Model
{
    //
    protected $fillable = ['post_id', 'user_id', 'invitation_answer'];
    protected $hidden = [];
    protected $table = "post_invitation";

    // public static function get_post_invitation($post_id){
    //     $invitation_list = DB::table('post_invitation')
    //             ->join('users', 'users.id', '=', 'post_invitation.user_id')
    //             ->select('post_invitation.*', 'users.username')
    //             ->where('post_invitation.post_id', $post_id)
    //             ->get();
    //     return $invitation_list;
    // }


    private static function select_invitation(){
        $invitation = DB::table('post_invitation')
                    ->join('posts', 'posts.id', '=', 'post_invitation.post_id')
                    ->select('post_invitation.*');
        return $invitation;
    }

    public static function get_user_invitation($user_id){
        $invitation_data = PostInvitation::select_invitation()
                    ->where('user_id', $user_id)->get();
        return $invitation_data;
    }

    public static function get_post_invitation($post_id){
        $invitation_data = PostInvitation::select_invitation()
                    ->where('post_id', $post_id)->get();
        return $invitation_data;
    }
}
