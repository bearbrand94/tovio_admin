<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;
Use App\Comment;
Use App\Like;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;

class LikeService extends WebService
{
    public function index()
    {
        return $this->createSuccessMessage(Like::all());
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Like::find($id));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $table_name = $request->table_name;
        $reference_id = $request->reference_id;

        $like_exist = Like::where('user_id', Auth::id())->where('table_name', $table_name)->where('reference_id', $reference_id)->count();
        if($like_exist<=0){
            $like = new Like();
            $like->user_id = $user_id;
            $like->table_name = $table_name;
            $like->reference_id = $reference_id;
            $like->save();
        }
        $return_data;
        if($table_name == "posts"){
            $return_data = Post::get_post_by_id($reference_id)[0];
        }
        else{
            $return_data = Comment::get_comment_by_id($reference_id)[0];
        }
        return $this->createSuccessMessage($return_data);
    }

    public function update(Request $request, $id)
    {
        $like = Like::findOrFail($id);
        $like->update($request->all());

        return $this->createSuccessMessage($like);
    }

    public function delete(Request $request)
    {
        $user_id = $request->user_id;
        $table_name = $request->table_name;
        $reference_id = $request->reference_id;

        $like = Like::where('user_id', Auth::id())->where('table_name', $table_name)->where('reference_id', $reference_id);
        if($like != null){
            $like->delete();
            if($request->table_name == "posts"){
                $like = Post::get_post_by_id($request->reference_id)[0];
            }
            else{
                $like = Comment::get_comment_by_id($request->reference_id)[0];
            }
        }
        else{
            $like = "Not Found";
        }

        return $this->createSuccessMessage($like);
    }
}
