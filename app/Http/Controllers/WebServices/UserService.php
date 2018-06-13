<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\User;

class UserService extends WebService
{
    public function index(){
    	return User::all();
    }

    public function show($id){
        return $this->createSuccessMessage(User::find($id));
    }
}
