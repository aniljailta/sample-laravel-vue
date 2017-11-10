<?php

namespace App\Providers\Orders;

use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\Orders\OrderService');
        $this->app->bind('App\Services\Orders\OrderCustomerFormService');
        $this->app->bind('App\Services\Orders\Dealer\OrderDealerFormService');
        $this->app->bind('App\Services\Orders\OrderPdfService');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Services\Orders\OrderService',
            'App\Services\Orders\OrderCustomerFormService',
            'App\Services\Orders\Dealer\OrderDealerFormService',
            'App\Services\Orders\OrderPdfService'
        ];
    }
}
