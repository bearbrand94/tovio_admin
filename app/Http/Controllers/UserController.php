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
        		->join('networks as follower', 'follower.follower_id', '=', 'users.id')
        		->join('networks as following', 'following.following_id', '=', 'users.id')
        		->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.telephone', DB::raw('count(follower.*) as follower_count'), DB::raw('count(following.*) as following_count'))
        		->groupBy('users.id');

        return Datatables::of($user)->make(true);
    }
}
