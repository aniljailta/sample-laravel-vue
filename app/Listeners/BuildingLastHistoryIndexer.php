<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Building;
use App\Models\BuildingStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingLastHistoryIndexer
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
        // Update building last status ID
        if (!empty($event->added)) {
            $buildingLastStatus = $event->added;
            $building = $buildingLastStatus->building()->get()->first();
            $building->status_id = $buildingLastStatus->id;
            $building->save();
        }

        // Update building last status ID
        if (!empty($event->removed)) {
            $removedStatus = $event->removed;
            $building = $removedStatus->building;
            $lastStatus = $building->building_history()->get()->last();
            $building->status_id = $lastStatus->id;
            $building->save();
        }
    }
}
