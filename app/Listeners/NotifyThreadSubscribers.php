<?php

namespace App\Listeners;

use App\Events\ReplyPosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\ThreadUpdated;

class NotifyThreadSubscribers
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
     * The filter ensures that any user who is subscribed to the thread
     * will not get ThreadUpdate notification if they are @mentioned,
     * instead they will only get the mention notification.
     *
     * @param  ReplyPosted  $event
     * @return void
     */
    public function handle(ReplyPosted $event)
    {
        $userNames = $event->reply->mentionedUsers->pluck('username');
        
        $event->thread
            ->subscriptions
            ->whereNotIn('user_id', $event->reply->user_id)
            ->filter(function ($subscription) use ($event, $userNames) {
                return $subscription
                ->user()
                ->whereIn('username', $userNames)
                ->doesntExist();
            })
            ->each(function ($subscription) use ($event) {
                $subscription->user->notify(new ThreadUpdated($event->thread, $event->reply));
            });
    }
}
