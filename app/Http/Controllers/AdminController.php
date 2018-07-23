<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function index(){
		return view('home');
	}

	public function event(){
		return view('event_list');
	}

	public function event_detail(Request $request){
		$event_id = $request->id;
		return view('event_detail');
	}

	public function user(){
		return view('user');
	}

	public function user_detail(Request $request){
		return view('user_detail');
	}
}
