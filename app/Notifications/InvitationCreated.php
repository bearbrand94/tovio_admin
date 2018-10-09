<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Post;
use App\PostInvitation;

class InvitationCreated extends Notification
{
    use Queueable;
    public $post_invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PostInvitation $post_invitation)
    {
        //
        $this->post_invitation = $post_invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $post_data = Post::find($this->post_invitation->post_id);
        $user_data = User::find($post_data->posted_by);

        // format date: Thursday, 27 June 9:04 AM
        //                  l   , j   F   g:i  A
        $post_date_format = Date('l, j F g:i A',strtotime($post_data->schedule_date));

        return [
            'reference_id' => $this->post_invitation->post_id,
            'reference_Table' => "post_invitation",
            'message' => $user_data->username . " invited you to join activity on " . $post_date_format . " : " . $post_data->title,
        ];
    }
}
