<?php

namespace App\Events;

use App\Models\BuildingPackage;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingPackageWasUpdated extends Event
{
    use SerializesModels;

    public $buildingPackage;

    /**
     * Create a new event instance.
     *
     * @param BuildingPackage $buildingPackage
     */
    public function __construct(BuildingPackage $buildingPackage)
    {
        $this->buildingPackage = $buildingPackage;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['buildingPackage.' . $this->buildingPackage->id];
    }
}
