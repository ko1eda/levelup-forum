<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Inspections\SpamManager;
use App\Inspections\InvalidKeywords;
use App\Inspections\RepeatedCharacters;

class SpamServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SpamManager::class, function ($app) {
            return new SpamManager(new InvalidKeywords, new RepeatedCharacters);
        });
    }
}
