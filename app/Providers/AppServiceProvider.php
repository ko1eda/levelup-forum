<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Redis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // load all channels into the navbar dropdown
        \View::composer(['shared.navbar', 'threads.create'], function ($view) {
            if ($channels = Redis::get('channels:list')) {
                $channels = unserialize($channels);

                return $view->with(compact('channels'));
            }

            $channels = \App\Channel::latest()->get();

            // store channels for one day in redis
            Redis::setex('Channels:list', (60 * 60 * 24), serialize($channels));

            
            return $view->with(compact('channels'));
        });

        // \Debugbar::disable();

        // .blade templates for pagination
        Paginator::defaultView('shared.pagination');
        // Paginator::defaultSimpleView('pagination::simple');
        
        // Store polymorphic relationships by
        // name, not class path
        Relation::morphMap([
            'reply' => \App\Reply::class,
            'thread' => \App\Thread::class,
            'favorite' => \App\Favorite::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      //
    }
}
