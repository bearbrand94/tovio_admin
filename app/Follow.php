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

    // Get all users who are following me
	public function followers()
	{
	    return $this->belongsToMany(User, 'networks', 'follower_id', 'following_id')->withTimestamps();
	}

	// Get all users we are following
	public function following()
	{
	    return $this->belongsToMany(User, 'networks', 'following_id', 'follower_id')->withTimestamps();
	}
}
