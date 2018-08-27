<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Follow;
Use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FollowService extends WebService
{
    public function index()
    {
        $network = [];
        $network['follower'] = User::find(Auth::id())->followers()->get();
        $network['following'] = User::find(Auth::id())->following()->get();
        $network['networks'] = User::get_network(Auth::id());
        return $this->createSuccessMessage($network);
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Like::find($id));
    }

    public function store(Request $request)
    {
        $follow_data = [];
        $follow_data['follower_id'] = $request->follower_id ? $request->follower_id : Auth::id();
        $follow_data['following_id'] = $request->following_id;
        $follow = Follow::create($follow_data);

        $return_data = [];
        $return_data['user_data'] = User::getUserDetail($request->following_id);
        $return_data['follow_data'] = User::getFollowData($request->following_id);

        return $this->createSuccessMessage($return_data);
    }

    public function update(Request $request, $id)
    {
        $follow = Follow::findOrFail($id);
        $follow->update($request->all());

        return $this->createSuccessMessage($follow);
    }

    public function delete(Request $request)
    {
        $follow_data = [];
        $follow_data['follower_id'] = $request->follower_id ? $request->follower_id : Auth::id();
        $follow_data['following_id'] = $request->following_id;

        $unfollow = Follow::where('follower_id', $follow_data['follower_id'])->where('following_id', $follow_data['following_id']);
        if($unfollow != null){
            $unfollow->delete();
            $return_data = User::getUserDetail($request->following_id);
            for ($i=0; $i < count($return_data) ; $i++) { 
                $return_data[$i]->following_data = User::getFollowData($request->following_id);
            }
            return $this->createSuccessMessage($return_data);
        }
        else{
            $unfollow = "Not Found";
            return $this->createSuccessMessage($unfollow);
        }
    }
}
