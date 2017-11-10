<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HelperService;

class HelperServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('helper', function() {
            return new HelperService();
        });
    }
}
