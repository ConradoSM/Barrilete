<?php

namespace barrilete\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsersCommentReaction extends Notification implements ShouldBroadcast
{
    use Queueable;

    private $reaction;

    /**
     * Create a new notification instance.
     *
     * @param $reaction
     */
    public function __construct($reaction)
    {
        $this->reaction = $reaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the broadcast representation of the notification.
     * @param  mixed  $notifiable
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

    /**
     * Get the array representation of the notification.
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'from' => $this->reaction['from'],
            'to' => $this->reaction['to'],
            'link' => $this->reaction['link'],
            'reaction' => $this->reaction['reaction']
        ];
    }
}
