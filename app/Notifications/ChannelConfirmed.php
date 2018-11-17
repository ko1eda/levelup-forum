<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Channel;

class ChannelConfirmed extends Notification
{
    use Queueable;

    /**
     * The approved channel
     *
     * @var App\Channel
     */
    protected $channel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
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
            'username' => '@'. config('forum.admin.username'),
            'action' => 'Your channel ' . $this->channel->name . ' was approved',
            'messageSub' => 'Check it out',
            'link' =>   route('threads.index', $this->channel, false)
        ];
    }
}
