<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['tag', 'table_name', 'reference_id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
