<?php

namespace App\Providers\Files;

use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\Files\FileService');
        $this->app->bind('App\Services\Files\PdfWrapperService');
        $this->app->bind('App\Services\Files\ImageService');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Services\Files\FileService',
            'App\Services\Files\PdfWrapperService',
            'App\Services\Files\ImageService'
        ];
    }
}
