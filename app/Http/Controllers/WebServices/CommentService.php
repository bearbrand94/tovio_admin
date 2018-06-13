<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;
Use App\Comment;
Use App\Like;
use Illuminate\Support\Facades\DB;

class CommentService extends WebService
{
    public function index()
    {
        return $this->createSuccessMessage(Comment::all());
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Comment::find($id));
    }

    public function get_post_comment(Request $request, $post_id){
        // return $request->user_id;
        // $post = Post::all();

        $post['post_data'] = Post::where('id', $post_id)->get();
        $post['post_data'][0]['like_count'] = Like::where('reference_id', $post['post_data'][0]['id'])->where('table_name', 'posts')->count();

        $post['comment_count'] = Comment::where('post_id', $post_id)->count();
        $post['comment'] = Comment::where('post_id', $post_id)->get();

        for ($i=0; $i < count($post['comment']); $i++) { 
            $post['comment'][$i]['like_count'] = Like::where('reference_id', $post['comment'][$i]['id'])->where('table_name', 'comments')->count();
        }
        return $this->createSuccessMessage($post);
    }

    public function store(Request $request, $post_id)
    {
        // 'post_id', 'content', 'commented_by', 'comment_date'
        $post_id = $post_id;
        $content = $request->content;
        $commented_by = $request->commented_by;
        $comment_date = $request->comment_date;

        $post = new Comment();
        $post->post_id = $post_id;
        $post->content = $content;
        $post->commented_by = $commented_by;
        $post->comment_date = Date('Y-m-d h:i:s',strtotime($comment_date));
        
        $post->save();

        return $this->createSuccessMessage($post);

        // $post = Post::create($request->all());
        // return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());

        return $this->createSuccessMessage($comment);
    }

    public function delete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return $this->createSuccessMessage($comment);
    }
}
