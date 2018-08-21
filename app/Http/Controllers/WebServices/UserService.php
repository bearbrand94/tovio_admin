<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Validator;
use AWS;
use Session;


class UserService extends WebService
{

	public function searchUser(Request $request){
		return $this->createSuccessMessage(User::search_user($request->keyword));
	}

    public function signIn(Request $request) {
		return $this->mobileSignIn($request->username, $request->password);
	}

	public function mobileSignIn($username, $password) {
		// check if user is exist, and password is alright
        $user_exist = $username?true:false;
        $password_correct = $password?true:false;

        if (Auth::attempt(array('username' => $username, 'password' => $password),true))
        {
        	$user = [];
            $user['data_auth'] = Auth::user();
            return $this->createSuccessMessage($user);
        }
        return $this->createErrorMessage('Username atau password anda salah', 400);
	}

	public function signUp(Request $request) {

        $path = $request->file('user_image')->store('public/users');

		$email = strtolower($request->email);
		$username = $request->username;
		$password = $request->password;
		$first_name = $request->first_name;
		$last_name = $request->last_name;
		$telephone = $request->telephone;
		$address = $request->address;
		$original_image_url = $request->original_image_url;
		$medium_image_url = $request->medium_image_url;
		$thumbnail_image_url = $request->thumbnail_image_url;
		$keterangan = $request->keterangan;

		$validator = Validator::make(
			array(
				"first_name"=>$first_name,
				"last_name"=>$last_name,
				"email"=>$email,
				"password"=>$password,
				"telephone"=>$telephone,
				"username"=>$username
			),
			array(
				"first_name" => 'required|min:3',
				"last_name" => 'required|min:3',
				"email"=>'required|min:3|email|unique:users,email',
				"password" => 'required|min:3',
				"username" => 'required|min:3|unique:users,username',
				"telephone" => 'required|min:10|max:13|unique:users,telephone')
		);

		if ($validator->fails()){
			$messages = $validator->messages();
			foreach ($messages->all() as $key => $value) {
				return $result = $this->createErrorMessage($value, 400);
			}
		}

		$new_user = new User();
		$new_user->first_name = $first_name;
		$new_user->last_name = $last_name;
		$new_user->email = $email;
		$new_user->password = bcrypt($password);
		$new_user->telephone = $telephone;
		$new_user->username = $username;
		$new_user->address = $address;
        if($path){
            $new_user->original_image_url = $path;
        }
		$new_user->medium_image_url = $medium_image_url;
		$new_user->thumbnail_image_url = $thumbnail_image_url;
		$new_user->keterangan = $keterangan;

    	$new_user->save();
		if (Auth::attempt(array('username' => $username, 'password' => $password),true))
        {
            $user = Auth::user();
        }

		return $this->createSuccessMessage($new_user);
	}

	public function signOut(Request $request) {
		if (Auth::check()){
			// delete all the data in user device/token
			$user = Auth::user();
			Auth::logout();
			return $this->createSuccessMessage("success");
		};
		return $this->createSuccessMessage("already log out");
	}

	public function editUserProfile(Request $request){

        $path = $request->file('user_image')->store('public/users');
		        
		$id = $request->id;
		$email = strtolower($request->email);
		$username = $request->username;
		$first_name = $request->first_name;
		$last_name = $request->last_name;
		$telephone = $request->telephone;
		$address = $request->address;
		$original_image_url = $request->original_image_url;
		$medium_image_url = $request->medium_image_url;
		$thumbnail_image_url = $request->thumbnail_image_url;
		$keterangan = $request->keterangan;

		$new_user = User::find($id);

		if (!$new_user) {
			return $this->createErrorMessage('user not found',400);
		}

		$new_user->first_name = $first_name;
		$new_user->last_name = $last_name;
		$new_user->email = $email;
		$new_user->telephone = $telephone;
		$new_user->username = $username;
		$new_user->address = $address;
        if($path){
            $new_user->original_image_url = $path;
        }
		$new_user->medium_image_url = $medium_image_url;
		$new_user->thumbnail_image_url = $thumbnail_image_url;
		$new_user->keterangan = $keterangan;
		
		$new_user->save();

		return $this->createSuccessMessage($new_user);
	}

	public function get_user_list(Request $request){
		$page_show;
		$request->page_show ? $page_show=$request->page_show : $page_show=0;
        $user = User::get_user_list($page_show);
        for ($i=0; $i < count($user); $i++) { 
        	$user[$i]->network = User::get_network($user[$i]->id);

        	$user[$i]->network_count = count($user[$i]->network);
        }
        return $this->createSuccessMessage($user);
	}

	public function testUser(Request $request){
        return $this->createSuccessMessage("Test Request");
	}

	public function uploadPicture(Request $request){        
        $file = $request->file;
        $category = $request->category ? $request->category : "user";

        $data = $this->uploadS3($file,$category);

        return $this->createSuccessMessage($data);
    }

    public function initialData(Request $request){
    	$data = [];

    	if (Auth::user()) {
            $data['user'] = Auth::user();
        }

        return $this->createSuccessMessage($data);
    }

}
