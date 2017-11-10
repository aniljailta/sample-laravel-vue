<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Building
        'App\Events\BuildingWasAdded' => [
            'App\Listeners\BuildingAddHistory',
            'App\Listeners\BuildingAddLocation',
        ],
        'App\Events\BuildingWasUpdated' => [
            'App\Listeners\BuildingTotalRecalculate',
        ],

        // Building Model
        'App\Events\BuildingModelWasUpdated' => [
        ],

        // Building History
        'App\Events\BuildingHistoryWasAdded' => [
            'App\Listeners\BuildingLastHistoryIndexer',
        ],

        // Building History
        'App\Events\BuildingHistoryWasRemoved' => [
            'App\Listeners\BuildingLastHistoryIndexer',
        ],

        // Building Location
        'App\Events\BuildingLocationWasRemoved' => [
            'App\Listeners\BuildingLastLocationIndexer',
        ],

        // Building Location
        'App\Events\BuildingLocationWasAdded' => [
            'App\Listeners\BuildingLastLocationIndexer',
        ],

        // Building Package
        'App\Events\BuildingPackageWasUpdated' => [
            'App\Listeners\BuildingPackageTotalRecalculate',
        ],

        // Order
        'App\Events\Orders\OrderWasUpdated' => [
            'App\Listeners\Orders\RecalculateCommission',
        ],
        'App\Events\Orders\OrderCustomerWasUpdated' => [
            'App\Listeners\Orders\CreateCustomerAccount'
        ],
        'App\Events\Orders\OrderCancelled' => [
            'App\Listeners\Orders\OrderCancelledListener',
        ],

        // File Esigned
        'App\Events\FileWasSigned' => [
            'App\Listeners\UpdateFileSign',
        ],
        'App\Events\FileWasSignedByCustomer' => [
            'App\Listeners\Orders\SendOrderDocumentsToCustomer',
        ],
        'App\Events\FileWasAllSigned' => [
            'App\Listeners\DownloadOrderEsignedDocuments',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
