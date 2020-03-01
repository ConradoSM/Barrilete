<?php

namespace barrilete\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsersCommentReply extends Notification implements ShouldBroadcast
{
    use Queueable;

    private $reply;

    /**
     * Create a new notification instance.
     *
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the database representation of the notification.
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'from' => $this->reply['from'],
            'to' => $this->reply['to'],
            'link' => $this->reply['link'],
            'reaction' => $this->reply['reaction']
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'data' => [
                'notification' => 1
            ]
        ]));
    }
}
