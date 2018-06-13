<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'posted_by', 'schedule_date', 'is_completed'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
