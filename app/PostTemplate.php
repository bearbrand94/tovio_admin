<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PostTemplate extends Model
{
    //
    protected $fillable = ['post_id', 'user_id', 'invitation_answer'];
    protected $hidden = [];
    protected $table = "post_templates";

    public static function get_post_template(){
        $invitation_list = DB::table('post_templates')
                ->select('post_templates.*')
                ->get();
        return $invitation_list;
    }
}
