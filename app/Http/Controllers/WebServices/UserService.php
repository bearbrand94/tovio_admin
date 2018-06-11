<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\User;

class UserService extends WebService
{
    public function index(){
    	echo 'User Service Called';
    }

	public function get_user(){
	    return User::all();
	}

	public function print(){
		echo "Print";
	}
}
