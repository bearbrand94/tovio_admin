<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'table_name', 'reference_id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
