<?php

namespace App\Events;

use App\Models\User;
use App\Models\Building;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingWasAdded extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Building $building
     * @param User $user
     * @param $defaultStatus
     */
    public function __construct(Building $building, $user, $defaultStatus = false)
    {
        $this->building = $building;
        $this->user = $user;
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
