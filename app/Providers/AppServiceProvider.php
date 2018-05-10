<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Pagination\Paginator;

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

        \Debugbar::disable();

        // .blade templates for pagination
        Paginator::defaultView('shared.pagination');
        // Paginator::defaultSimpleView('pagination::simple');
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
