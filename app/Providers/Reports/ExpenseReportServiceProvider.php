<?php

namespace App\Providers\Reports;

use Illuminate\Support\ServiceProvider;

class ExpenseReportServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\Reports\ExpenseReportService');
        $this->app->bind('App\Repositories\ExpenseRepository');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Services\Reports\ExpenseReportService',
            'App\Repositories\ExpenseRepository'
        ];
    }
}
