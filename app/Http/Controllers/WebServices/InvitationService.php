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

class InvitationService extends WebService
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

    private static function select_invitation(){
        $invitation = DB::table('post_invitation')
                    ->join('posts', 'posts.id', '=', 'post_invitation.post_id')
                    ->select('post_invitation.*');
        return $invitation;
    }

    public static function get_user_invitation(Request $request){
        $validator = Validator::make(
            array(
                "user_id"=> $request->user_id
            ),
            array(
                "user_id" => 'required'
            )
        );

        if ($validator->fails()){
            $messages = $validator->messages();
            foreach ($messages->all() as $key => $value) {
                return $result = $this->createErrorMessage($value, 400);
            }
        }

        $invitation_data = PostInvitation::get_user_invitation($request->user_id);
        return $invitation_data;
    }

    public static function get_post_invitation(Request $request){
        $validator = Validator::make(
            array(
                "post_id"=> $request->post_id
            ),
            array(
                "post_id" => 'required'
            )
        );

        if ($validator->fails()){
            $messages = $validator->messages();
            foreach ($messages->all() as $key => $value) {
                return $result = $this->createErrorMessage($value, 400);
            }
        }

        $invitation_data = PostInvitation::get_post_invitation($request->post_id);
        return $invitation_data;
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

        return $this->createSuccessMessage(PostInvitation::get_post_invitation($post_id));
    }

    public function store(Request $request)
    {    
        $new_post_data = PostInvitation::invite($request);
        return $this->createSuccessMessage($new_post_data);
    }

    public function accept_invitation(Request $request)
    {
        $invitation_id = $request->invitation_id;
        $validator = Validator::make(
            array(
                "invitation_id"=>$invitation_id,
            ),
            array(
                "invitation_id" => 'required',
            )
        );
        if ($validator->fails()){
            $messages = $validator->messages();
            foreach ($messages->all() as $key => $value) {
                return $result = $this->createErrorMessage($value, 400);
            }
        }

        $post_invitation = PostInvitation::find($request->invitation_id);
        if($post_invitation->user_id == Auth::id()){
            $post_invitation->invitation_answer = 1;
            $post_invitation->update();            
        }
        else{
            return $this->createSuccessMessage("You are not allowed to do that.");
        }

        
        $new_post_data = PostInvitation::get_post_invitation($invitation_id);
        return $this->createSuccessMessage($post_invitation);
    }

    public function reject_invitation(Request $request)
    {
        $invitation_id = $request->invitation_id;
        $validator = Validator::make(
            array(
                "invitation_id"=>$invitation_id,
            ),
            array(
                "invitation_id" => 'required',
            )
        );
        if ($validator->fails()){
            $messages = $validator->messages();
            foreach ($messages->all() as $key => $value) {
                return $result = $this->createErrorMessage($value, 400);
            }
        }
        $post_invitation = PostInvitation::find($invitation_id);
        if($post_invitation->user_id == Auth::id()){
            $post_invitation->invitation_answer = 2;
            $post_invitation->update();            
        }
        else{
            return $this->createSuccessMessage("You are not allowed to do that.");
        }
        
        $new_post_data = PostInvitation::get_post_invitation($post_invitation->id);
        return $this->createSuccessMessage($post_invitation);
    }

    public function update(Request $request)
    {
        $post_invitation = PostInvitation::find($request->invitation_id);
        $post_invitation->update($request->all());
        
        $new_post_data = PostInvitation::get_post_invitation($invitation_id)[0];
        
        return $this->createSuccessMessage($new_post_data);
    }

    public function delete(Request $request)
    {
        $post_invitation = PostInvitation::find($request->invitation_id);
        if($post_invitation != null){
            $post_invitation->delete();
        }
        else{
            $post_invitation = "Not Found";
        }
        return $this->createSuccessMessage($post_invitation);
    }
}
