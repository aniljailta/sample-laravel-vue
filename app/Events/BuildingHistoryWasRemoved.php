<?php

namespace App\Events;

use App\Models\BuildingHistory;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingHistoryWasRemoved extends Event
{
    use SerializesModels;

    public $removed;

    /**
     * Create a new event instance.
     *
     * @param BuildingHistory $buildingHistory
     * @internal param Building $building
     */
    public function __construct(BuildingHistory $buildingHistory)
    {
        $this->removed = $buildingHistory;
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
