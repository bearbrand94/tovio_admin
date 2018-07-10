<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\Post;
Use App\Comment;
Use App\Like;
use Illuminate\Support\Facades\DB;

class CommentService extends WebService
{
    public  $page_show = 10;

    public function index()
    {
        return $this->createSuccessMessage(Comment::all());
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Comment::find($id));
    }

    public function get_comment(Request $request){
        $comment['comment'] = Comment::find($request->comment_id)->paginate($this->page_show);
        $comment['like_count'] = Like::where('reference_id', $request->comment_id)->where('table_name', 'comments')->count();
        $comment['liked_by_me'] = Like::where('reference_id', $request->comment_id)->where('user_id', $request->user_id)->where('table_name', 'comments')->count();       
        return $this->createSuccessMessage($comment);
    }

    public function get_post_comment(Request $request){
        $post_id = request()->post_id;

        $post_id = $request->post_id;
        $post['post_data'] = Post::get_post_by_id($post_id);
        if(count($post['post_data'])>0){
            $post['comment_data'] = Comment::get_post_comment($post_id);
        }
        else{
            $post['info'] = "Post Not Available";
            $post['data'] = $post_id;
        }
        return $this->createSuccessMessage($post);
    }

    public function store(Request $request)
    {
        // 'post_id', 'content', 'commented_by', 'comment_date'
        $post_id = $request->post_id;
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

    public function update(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        $comment->update($request->all());

        return $this->createSuccessMessage($comment);
    }

    public function delete(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        if($comment != null){
            $comment->delete();
        }
        else{
            $comment = "Not Found";
        }

        return $this->createSuccessMessage($comment);
    }
}
