<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Follow extends Model
{
    protected $fillable = ['follower_id', 'following_id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $table = "networks";

}
