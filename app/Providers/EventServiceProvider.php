<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ReplyPosted' => [
            'App\Listeners\NotifyMentionedUsers',
            'App\Listeners\NotifyThreadSubscribers'
        ],
        
        // When a user is registered
        'Illuminate\Auth\Events\Registered'  => [
            \App\Listeners\Registration\SendConfirmationEmail::class,
            \App\Listeners\Registration\NotifyNewlyRegisteredUser::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
