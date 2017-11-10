<?php

namespace App\Events;

use App\Models\Building;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingWasUpdated extends Event
{
    use SerializesModels;

    public $building;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Building $building)
    {
        $this->building = $building;
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
