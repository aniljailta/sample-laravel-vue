<?php

namespace App\Events;

use App\Models\BuildingLocation;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingLocationWasRemoved extends Event
{
    use SerializesModels;

    public $removed;

    /**
     * Create a new event instance.
     *
     * @param BuildingLocation $buildingLocation
     */
    public function __construct(BuildingLocation $buildingLocation)
    {
        $this->removed = $buildingLocation;
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
