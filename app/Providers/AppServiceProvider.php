<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;

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
            $channels = \App\Channel::latest()->get();
            
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
