<?php

namespace App\Events;

use App\Models\BuildingLocation;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingLocationWasAdded extends Event
{
    use SerializesModels;

    public $added;

    /**
     * Create a new event instance.
     *
     * @param BuildingLocation $buildingLocation
     * @internal param Building $building
     */
    public function __construct(BuildingLocation $buildingLocation)
    {
        $this->added = $buildingLocation;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['building.' . $this->building->id];
    }
}
