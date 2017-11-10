<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\BuildingLocation;
use App\Events\BuildingWasAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingAddLocation
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
     * @param BuildingWasAdded|BuildingWasUpdated $event
     */
    public function handle(BuildingWasAdded $event)
    {
        // Update location
        $building_location = new BuildingLocation([
            'building_id' => $event->building->id,
            'location_id' => $event->building->plant->location_id
        ]);
        
        if ($event->user) {
            $building_location->user_id = $event->user->id;
        }

        $building_location = $event->building->building_locations()->save($building_location);
        $event->building->update(['last_location_id' => $building_location->id]);
    }
}
