<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Thread;
use App\Reply;
use App\User;

class UserMentioned extends Notification
{
    use Queueable;

    /**
     * $thread
     *
     * @var $thread
     */
    protected $thread;


    /**
     * $reply
     *
     * @var $reply
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

    // /**
    //  * Get the mail representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return \Illuminate\Notifications\Messages\MailMessage
    //  */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'username' => '@'.$this->reply->user->username,
            'action' => 'mentioned you in ',
            'messageFull' => $this->thread->title,
            'messageSub' => substr($this->thread->title, 0, 35) .'...',
            'link' => route('threads.show', [$this->thread->channel, $this->thread, $this->thread->slug], false).'#reply-'.$this->reply->id
        ];
    }
}
