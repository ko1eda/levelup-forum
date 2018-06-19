<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Thread;
use App\Reply;
use App\Interfaces\NotificationInterface;

class ThreadUpdated extends Notification implements NotificationInterface
{
    use Queueable;

    /**
     * $thread
     *
     * @var App\Thread $thread
     */
    protected $thread;

    /**
     * $reply
     *
     * @var @var App\Reply $reply
     */
    protected $reply;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
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
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => substr($this->reply->body, 0, 25) .'...',
            'username' => $this->reply->user->name
        ];
    }
}
