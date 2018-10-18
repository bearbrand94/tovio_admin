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
use Illuminate\Support\Facades\Storage;

class UserService extends WebService
{

	public function searchUser(Request $request){
		$user_data = User::search_user($request->keyword);
		
		for ($i=0; $i < count($user_data); $i++) {
        	$user_data[$i]->network = User::get_network($user_data[$i]->id);
        	$user_data[$i]->network_count = count($user_data[$i]->network);
			$user_data[$i]->follow_data = User::getFollowData($user_data[$i]->id);
		}
		return $this->createSuccessMessage($user_data);
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
        // 	$user = [];
        //     $user['data_auth'] = Auth::user();
        //     return $this->createSuccessMessage($user);
        	$data = [];
    
        	if (Auth::user()) {
	        	$data = User::current_user_data();
            }
    
            return $this->createSuccessMessage($data);
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

		$gender = $request->gender;
		$birthday = null;
		if($request->birthday ){
		    $birthday = Date('Y-m-d h:i:s',strtotime($request->birthday));
		};
		
		$company = $request->company;
		$description = $request->description;
		$website = $request->website;

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
				"username"=>$username
			),
			array(
				"first_name" => 'required|min:3',
				"last_name" => 'required|min:3',
				"email"=>'required|min:3|email|unique:users,email',
				"password" => 'required|min:3',
				"username" => 'required|min:3|unique:users,username'
			)
		);

		if ($validator->fails()){
			$messages = $validator->messages();
			foreach ($messages->all() as $key => $value) {
				return $result = $this->createErrorMessage($value, 400);
			}
		}

		$new_user = new User();
		$new_user->username = $username;
		$new_user->first_name = $first_name;
		$new_user->last_name = $last_name;
		$new_user->email = $email;
		$new_user->password = bcrypt($password);
		$new_user->telephone = $telephone;
		$new_user->username = $username;
		$new_user->address = $address;

		$new_user->gender = $gender;
		$new_user->birthday = $birthday;
		$new_user->company = $company;
		$new_user->description = $description;
		$new_user->website = $website;
        
        if($request->file('user_image')){
            $contents = $request->file('user_image');
            $path = Storage::disk('public')->put('users', $contents);
            if($path){
                $new_user->original_image_url = url("storage/app/public/") . "/" . $path;
            }
        }

		$new_user->medium_image_url = $medium_image_url;
		$new_user->thumbnail_image_url = $thumbnail_image_url;
		$new_user->keterangan = $keterangan;

    	$new_user->save();
		if (Auth::attempt(array('username' => $username, 'password' => $password),true))
        {
            $data = User::current_user_data();
        }

		return $this->createSuccessMessage($data);
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

		$gender = $request->gender;
		$birthday = Date('Y-m-d h:i:s',strtotime($request->birthday));
		$company = $request->company;
		$description = $request->description;
		$website = $request->website;

		$original_image_url = $request->original_image_url;
		$medium_image_url = $request->medium_image_url;
		$thumbnail_image_url = $request->thumbnail_image_url;
		$keterangan = $request->keterangan;

		$new_user = User::find($id);

		if (!$new_user) {
			return $this->createErrorMessage('user not found',400);
		}

		$username ? $new_user->username = $username : $new_user->username;
		$first_name ? $new_user->first_name = $first_name : $new_user->first_name;
		$last_name ? $new_user->last_name = $last_name : $new_user->last_name;
		// $new_user->email = $email;
		$telephone ? $new_user->telephone = $telephone : $new_user->telephone;
		// $new_user->username = $username;
		$address ? $new_user->address = $address : $new_user->address;

		$gender ? $new_user->gender = $gender : $new_user->gender;
		$birthday ? $new_user->birthday = $birthday : $new_user->birthday;
		$company ? $new_user->company = $company : $new_user->company;
		$description ? $new_user->description = $description : $new_user->description;
		$website ? $new_user->website = $website : $new_user->website;

        if($request->file('user_image')){
            $contents = $request->file('user_image');
            $path = Storage::disk('public')->put('users', $contents);
            if($path){
                $new_user->original_image_url = url("storage/app/public/") . "/" . $path;
            }
        }

		$new_user->medium_image_url = $medium_image_url;
		$new_user->thumbnail_image_url = $thumbnail_image_url;
		$new_user->keterangan = $keterangan;
		
		$new_user->save();
        $user_data = User::getUserDetail($new_user->id)[0];
		return $this->createSuccessMessage($user_data);
	}

	public function get_user_list(Request $request){
		$page_show;
		$request->page_show ? $page_show=$request->page_show : $page_show=0;
        $user = User::get_user_list($page_show);
        for ($i=0; $i < count($user); $i++) { 
        	$user[$i] = User::getUserDetail($user[$i]->id);
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
    	if (Auth::check()){
	        return $this->createSuccessMessage(User::current_user_data());
	    }
	    else{
	    	return $this->createErrorMessage('You need to log in first.', 400);
	    }
    }

    public function readNotification(Request $request){
    	$user = Auth::user();
		foreach ($user->unreadNotifications as $notification) {
			if($notification->id == $request->notification_id){
				$notification->markAsRead();
			}
		}
		return $this->createSuccessMessage(User::current_user_data());
    }

    public function readAllNotification(Request $request){
    	$user = Auth::user();
		$user->unreadNotifications->markAsRead();
		return $this->createSuccessMessage(User::current_user_data());
    }

    public function getNotification(Request $request){
    	$user = Auth::user();
    	$data['notification'] = [];
    	foreach ($user->notifications as $notification) {
		    array_push($data['notification'], $notification);
		}
		return $this->createSuccessMessage($data);
    }
}
