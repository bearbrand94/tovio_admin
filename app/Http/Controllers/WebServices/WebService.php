<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WebService extends BaseController
{
    public function index(){
    	echo 'Web Service Called';
    }

	public function show($name){
	    return view('hello',array('name' => $name));
	}

	public function createErrorMessage($message, $errorCode){
		$result = [ "payload"=>'',
				    "error_msg"=>$message,
					"code"=>$errorCode
					];
		return response()->json($result, $errorCode);
	}

	public function createSuccessMessage($payload){
		$result = [ "payload"=>$payload,
				    "error_msg"=>'',
					"code"=>200
					];
		return response()->json($result);
	}
}
