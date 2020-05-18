<?php

namespace barrilete\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UsersMessages extends Notification implements ShouldBroadcast
{
    use Queueable;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
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
            'from' => $this->message['from'],
            'to' => $this->message['to'],
            'conversation_id' => $this->message['conversation_id'],
            'message' => $this->message['message']
        ];
    }
}
