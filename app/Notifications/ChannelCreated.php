<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Mail\Channel\ConfirmationSent as Mailable;

class ChannelCreated extends Notification
{
    use Queueable;

    /**
     * the uri containing the unique token
     * that retrieves the Channel from redis
     *
     * @var String $uri
     */
    protected $uri;

    
    /**
     * the user who requested the channels creation
     *
     * @var App\User $channelCreator
     */
    protected $channelCreator;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $channelCreator, String $token)
    {
        $this->channelCreator = $channelCreator;

        $this->uri = route('channels.confirm.create', 'tokenID=' . $token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new Mailable($this->channelCreator, $this->uri))->to($admin = $notifiable->email);
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
            'username' => '@'. $this->channelCreator->username,
            'action' => 'proposed a new channel ',
            'messageSub' => 'Check it out',
            'link' =>  $this->uri
        ];
    }
}
