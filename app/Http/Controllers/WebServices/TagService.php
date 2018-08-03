<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;
Use App\Comment;
Use App\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $like = Like::create($request->all());
        return $this->createSuccessMessage($like);
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
        }
        else{
            $like = "Not Found";
        }

        return $this->createSuccessMessage($like);
    }
}
