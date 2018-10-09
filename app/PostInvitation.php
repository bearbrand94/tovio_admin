<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostInvitation extends Model
{
    //
    protected $fillable = ['post_id', 'user_id', 'invitation_answer'];
    protected $hidden = [];
    protected $table = "post_invitation";
}
