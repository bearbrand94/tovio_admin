<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class PostInvitation extends Model
{
    //
    protected $fillable = ['post_id', 'user_id', 'invitation_answer'];
    protected $hidden = [];
    protected $table = "post_invitation";

    public static function get_post_invitation($post_id){
        $invitation_list = DB::table('post_invitation')
                ->join('users', 'users.id', '=', 'post_invitation.user_id')
                ->select('post_invitation.*', 'users.username')
                ->where('post_invitation.post_id', $post_id)
        return $invitation_list;
    }
}
