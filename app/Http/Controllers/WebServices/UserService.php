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
		$new_user->original_image_url = $original_image_url;
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
		$new_user->original_image_url = $original_image_url;
		$new_user->medium_image_url = $medium_image_url;
		$new_user->thumbnail_image_url = $thumbnail_image_url;
		$new_user->keterangan = $keterangan;
		
		$new_user->save();

		return $this->createSuccessMessage($new_user);
	}

	public function getUser(Request $request){
		$page = $request->page ? $request->page : 1;
        $show = $request->show ? $request->show : 15;
        $keyword = $request->keyword ? $request->keyword : null;
        $key_sort = $request->key_sort ? $request->key_sort : null;
        $sort_type = $request->sort_type ? $request->sort_type : 'asc';
        $user = User::getUser($page-1, $show, $keyword, $sort_type, $key_sort);
        return $this->createSuccessMessage($user);
	}

	public function testUser(Request $request){
        return $this->createSuccessMessage("Test Request");
	}

	public function uploadPicture(Request $request){        
        $file = $request->file;
        $category = $request->category;

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
