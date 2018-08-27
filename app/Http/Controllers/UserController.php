<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Yajra\Datatables\Datatables;
use DB;

class UserController extends Controller
{
	public function index(){
		return view('user');
	}
    public function get_user()
    {
        // return Datatables::of(User::query())->make(true);

        $user = DB::table('users')
        		->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.telephone',
        			DB::raw('(select count(*) from networks where follower_id = users.id) as following_count'),
        			DB::raw('(select count(*) from networks where following_id = users.id) as follower_count')
        		)
        		->groupBy('users.id');

        return Datatables::of($user)->make(true);
    }
}
