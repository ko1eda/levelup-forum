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
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(SpamFree::class, function ($app) {
        //     return new SpamFree(app(SpamManager::class));
        // });

        $this->app->bind(SpamManager::class, function ($app) {
            return new SpamManager(
                new InvalidKeywords(config('spam.blacklist')),
                new RepeatedCharacters
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [SpamManager::class];
    }
}
