<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistered extends Notification
{
    use Queueable;


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
     * Notification sent from forum admin to
     * remind the user to register their email.
     *
     * Note this data structure is necessary for
     * the NotificationWidget.Vue component
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'username' => '@' . config('forum.admin'),
            'action' => 'Reminder,',
            'message' => 'register you email to participate in the forums!',
            'messageSub' => 'register you email to participate in the forums!',
            'link' => ''
        ];
    }
}
