<?php

namespace App\Providers\Building;

use Illuminate\Support\ServiceProvider;

class BuildingServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application service.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Building\BuildingService');
        $this->app->bind('App\Services\Building\BuildingImportService');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Services\Building\BuildingService',
            'App\Services\Building\BuildingImportService'
        ];
    }
}
