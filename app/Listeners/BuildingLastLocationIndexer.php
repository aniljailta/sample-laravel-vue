<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Building;
use App\Models\BuildingLocation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingLastLocationIndexer
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
     * @param $event
     */
    public function handle($event)
    {
        // Update building last location ID
        if (!empty($event->added)) {
            $buildingLastLocation = $event->added;
            $building = $buildingLastLocation->building()->get()->first();
            $building->last_location_id = $buildingLastLocation->id;
            $building->save();
        }

        // Update building last location ID
        if (!empty($event->removed)) {
            $removedStatus = $event->removed;
            $building = $removedStatus->building;
            $lastLocation = $building->building_locations()->get()->last();
            $building->last_location_id = $lastLocation->id;
            $building->save();
        }
    }
}
