<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'content', 'commented_by', 'comment_date'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
