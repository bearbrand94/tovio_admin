<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;

class PostService extends WebService
{
    public function index()
    {
        echo Post::all();
    }
 
    public function show($id)
    {
        return Post::find($id);
    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json($post, 200);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
}
