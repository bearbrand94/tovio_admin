<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    protected $fillable = [
        'user_id', 'token_value'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = "user_device_token";
}
