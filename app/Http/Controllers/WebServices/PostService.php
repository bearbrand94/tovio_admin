<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\User;
Use App\Post;
Use App\Comment;
Use App\Like;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;

class PostService extends WebService
{
    public $page_show;


    public function get_picture(Request $request)
    {
        $contents = Storage::get('posts/WkQDFCtmcPv5wUDnqPClmI2fWnQ00QEiH9NbzCu4.jpeg');
        return $contents;
    }

    public function upload_picture(Request $request)
    {
        $path = $request->file('post_image')->store('public/posts');
        return $path;
    }

    public function index()
    {
        return $this->createSuccessMessage(Post::all());
    }
 
    public function show($id)
    {
        return $this->createSuccessMessage(Post::find($id));
    }

    private function process_request_data(Request $request){
        $date_start = $request->date_start ? Date('Y-m-d h:i:s',strtotime($request->date_start)) : Date('Y-m-d h:i:s',strtotime(now()));
        // $date_end = $request->date_end ? Date('Y-m-d h:i:s',strtotime($request->date_end)) : Date('Y-m-d h:i:s',strtotime(now()));
        $page_show = $request->page_show ? $request->page_show : 10;

        $request_data = $request;
        $request_data->date_start = $date_start;
        // $request_data->date_end = $date_end;
        $request_data->page_show = $page_show;
        return $request_data;
    }

    public function get_post(Request $request){
        $request = $this->process_request_data($request);

        $post = Post::get_post($request->date_start, $request->date_end, $request->page_show);
        return $this->createSuccessMessage($post);
    }

    public function get_network_post(Request $request){
        $request = $this->process_request_data($request);

        $post = Post::get_network_post($request->date_start, $request->date_end, $request->page_show);

        return $this->createSuccessMessage($post);
    }

    public function get_my_post(Request $request){
        $request = $this->process_request_data($request);

        $post = Post::get_user_post($request->date_start, $request->date_end, $request->page_show, Auth::id());
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
        $posted_by != null ? $post->posted_by = $posted_by : $post->posted_by = Auth::id();
        // $post->posted_by = Auth::id();

        $contents = $request->file('post_image');
        $path = Storage::disk('public')->put('posts', $contents);
        if($path){
            $post->original_image_url = "storage/app/public/" . $path;
        }

        $post->save();

        return $this->createSuccessMessage($post);

        // $post = Post::create($request->all());
        // return response()->json($post, 201);
    }

    public function update(Request $request)
    {
        $post = Post::find($request->post_id);

        $contents = $request->file('post_image');
        $path = Storage::disk('public')->put('posts', $contents);
        if($path){
            $post->original_image_url = $path;
        }
        
        $post->update($request->all());

        return $this->createSuccessMessage($post);
    }

    public function delete(Request $request)
    {
        $post = Post::find($request->post_id);
        if($post != null){
            $post->delete();
        }
        else{
            $post = "Not Found";
        }
        return $this->createSuccessMessage($post);
    }

    public function uploadPicture(Request $request){        
        $file = $request->file;
        $category = $request->category ? $request->category : "post";

        $data = $this->uploadS3($file,$category);

        return $this->createSuccessMessage($data);
    }
}
