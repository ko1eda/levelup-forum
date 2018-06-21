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
     * the link is the hash link to the specific reply that caused
     * the notification.
     * 
     * Note: the false parameter in route() specifies absolute path
     * by setting it to fault we get the relative path
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => substr($this->reply->body, 0, 20) .'...',
            'username' => $this->reply->user->name,
            'link' => route('threads.show', [$this->thread->channel, $this->thread], false).'#reply-'.$this->reply->id
        ];
    }
}
