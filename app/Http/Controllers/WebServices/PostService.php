<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
Use App\User;
Use App\Post;
Use App\Comment;
Use App\Like;
Use App\PostInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Validator;

//notification
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvitationCreated;

class PostService extends WebService
{
    public $page_show;

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

        $post = Post::get_post($request->date_start, $request->date_end, $request->page_show, $request->order_by);
        return $this->createSuccessMessage($post);
    }

    public function get_network_post(Request $request){
        $request = $this->process_request_data($request);

        $post = Post::get_network_post($request->date_start, $request->date_end, $request->page_show, $request->order_by);
        return $this->createSuccessMessage($post);
    }

    public function get_my_post(Request $request){
        $request = $this->process_request_data($request);

        $post = Post::get_user_post($request->date_start, $request->date_end, $request->page_show, Auth::id(), $request->order_by  );
        return $this->createSuccessMessage($post);
    }

    public function store(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $schedule_date = $request->schedule_date;
        $posted_by = $request->posted_by;

		$validator = Validator::make(
			array(
				"title"=>$title,
				"content"=>$content,
				"schedule_date"=>$schedule_date
			),
			array(
				"title" => 'required|min:3',
				"content" => 'required|min:3',
				"schedule_date"=>'required'
			)
		);

		if ($validator->fails()){
			$messages = $validator->messages();
			foreach ($messages->all() as $key => $value) {
				return $result = $this->createErrorMessage($value, 400);
			}
		}
		
        $post = new Post();
        $post->title = $title;
        $post->content = $content;
        $post->schedule_date = Date('Y-m-d h:i:s',strtotime($schedule_date));
        $posted_by != null ? $post->posted_by = $posted_by : $post->posted_by = Auth::id();

        $post->post_type = $request->post_type ? $request->post_type : 0;
        
        if($request->image_url){
            $post->original_image_url = $request->image_url;
        }
        else{
            if($request->file('post_image')){
                $contents = $request->file('post_image');
                $path = Storage::disk('public')->put('posts', $contents);
                if($path){
                    $post->original_image_url = url("storage/app/public/") . "/" . $path;
                }
            };  
        };

        
        $post->save();
        
        $new_post_data = Post::get_post_by_id($post->id)[0];
        
        return $this->createSuccessMessage($new_post_data);

        // $post = Post::create($request->all());
        // return response()->json($post, 201);
    }

    public function update(Request $request)
    {
        $post = Post::find($request->post_id);
        
        if($request->image_url){
            $post->original_image_url = $request->image_url;
        }
        else{
            if($request->file('post_image')){
                $contents = $request->file('post_image');
                $path = Storage::disk('public')->put('posts', $contents);
                if($path){
                    $post->original_image_url = url("storage/app/public/") . "/" . $path;
                }
            };  
        };
        
        $post->update($request->all());
        
        $new_post_data = Post::get_post_by_id($post->id)[0];
        
        return $this->createSuccessMessage($new_post_data);
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

    public function upload_picture(Request $request)
    {
        $original_image_url="";
        if($request->file('post_image')){
            $contents = $request->file('post_image');
            $path = Storage::disk('public')->put('posts', $contents);
            if($path){
                $original_image_url = url("storage/app/public/") . "/" . $path;
            }
        };
        return $original_image_url;
    }

    public function invite(Request $request){
        $post_id = $request->post_id;
        $user_id = $request->user_id;
        // $invitation_answer = $request->invitation_answer ? 0;

        $validator = Validator::make(
            array(
                "post_id"=>$post_id,
                "user_id"=>$user_id
            ),
            array(
                "post_id" => 'required',
                "user_id" => 'required'
            )
        );

        if ($validator->fails()){
            $messages = $validator->messages();
            foreach ($messages->all() as $key => $value) {
                return $result = $this->createErrorMessage($value, 400);
            }
        }
        
        $post_invitation = new PostInvitation();
        $post_invitation->post_id = $post_id;
        $post_invitation->user_id = $user_id;
        $post_invitation->save();
        
        $user_notif = User::find($user_id);
        Notification::send($user_notif, new InvitationCreated($post_invitation));

        return $this->createSuccessMessage($post_invitation);
    }

    public function get_post_invitation(Request $request){
        return $this->createSuccessMessage(PostInvitation::get_post_invitation($request->post_id));
    }
}
