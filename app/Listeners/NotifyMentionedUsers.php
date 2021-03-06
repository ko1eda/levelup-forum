<?php

namespace App\Listeners;

use App\Events\ReplyPosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Notifications\UserMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all mentioned users for each reply
     * and for each user mentioned, send
     * a userMentioned notification.
     *
     * @param  ReplyPosted  $event
     * @return void
     */
    public function handle(ReplyPosted $event)
    {
        $event->reply->mentionedUsers
            ->each(function ($user) use ($event) {
                $user->notify(new UserMentioned($event->thread, $event->reply));
            });
    }
}
