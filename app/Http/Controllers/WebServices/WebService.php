<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WebService extends BaseController
{
    public function index(){
    	echo 'Web Service Called';
    }

	public function show($name)
	{
	    return view('hello',array('name' => $name));
	}
}
