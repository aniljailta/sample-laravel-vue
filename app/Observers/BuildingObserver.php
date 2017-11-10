<?php

namespace App\Observers;

use App\Models\Building;
use Auth;

use App\Events\BuildingWasAdded;
use App\Events\BuildingWasUpdated;

class BuildingObserver
{
    /**
     * Listen to the Building created event.
     *
     * @param  Building  $building
     * @return void
     */
    public function created(Building $building)
    {
        event(new BuildingWasAdded($building, Auth::user()));
    }

    /**
     * Listen to the Building updated event.
     *
     * @param  Building  $building
     * @return void
     */
    public function updating(Building $building)
    {

    }

    /**
     * Listen to the Building deleting event.
     *
     * @param  Building  $building
     * @return void
     */
    public function deleting(Building $building)
    {
        // $building->setChildRelations(['building_locations', 'building_history',]);

        // $building->building_history()->withTrashed()->delete();
        // $building->building_locations()->withTrashed()->delete();
        // $building->building_options()->delete();
    }
}