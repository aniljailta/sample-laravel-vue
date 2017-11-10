<?php

namespace App\Listeners;

use App\Events\BuildingPackageWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingPackageTotalRecalculate
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BuildingPackageWasUpdated $event
     */
    public function handle(BuildingPackageWasUpdated $event)
    {
        $buildingPackage = $event->buildingPackage;
        $buildingPackage->load('building_model');
        $buildingPackage->load('options.option');

        $totalOptions = 0;
        // Update totals
        $buildingPackage->options->each(function($item) use (&$totalOptions) {
            $totalOptions += $item->option->unit_price * $item->quantity;
        });

        if ($buildingPackage->building_model) {
            $buildingPackage->total_price = $totalOptions + $buildingPackage->building_model->shell_price;
            $buildingPackage->save();
        }
    }
}
