<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;
Use App\Comment;
Use App\Like;
use Illuminate\Support\Facades\DB;

class PostService extends WebService
{
    public function index()
    {
        return $this->createSuccessMessage(Post::all());
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Post::find($id));
    }

    public function get_user_post(Request $request){
        // return $request->user_id;
        $post = Post::where('posted_by', $request->user_id);
        // $post = Post::all();

        if($request->date_start && $request->date_end){
            $date_start = Date('Y-m-d h:i:s',strtotime($request->date_start));
            $date_end = Date('Y-m-d h:i:s',strtotime($request->date_end));
            $post = $post->where('schedule_date', '>', $date_start);
            $post = $post->where('schedule_date', '<', $date_end);
        }
        $post = $post->get();

        for ($i=0; $i < count($post); $i++) { 
            $post[$i]['comment_count'] = Comment::where('post_id', $post[$i]->id)->count();
            $post[$i]['like_count'] = Like::where('reference_id', $post[$i]->id)->where('table_name', 'posts')->count();
        }

        return $this->createSuccessMessage($post);
    }

    public function store(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $schedule_date = $request->schedule_date;
        $posted_by = $request->posted_by;

        $post = new Post();
        $post->title = $title;
        $post->content = $content;
        $post->schedule_date = Date('Y-m-d h:i:s',strtotime($schedule_date));
        $post->posted_by = $posted_by;
        $post->save();

        return $this->createSuccessMessage($post);

        // $post = Post::create($request->all());
        // return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());

        return $this->createSuccessMessage($post);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return $this->createSuccessMessage($post);
    }
}
