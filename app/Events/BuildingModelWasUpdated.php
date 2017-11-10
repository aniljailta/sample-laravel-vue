<?php

namespace App\Events;

use App\Models\BuildingModel;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuildingModelWasUpdated extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param BuildingModel $buildingModel
     * @param $data
     */
    public function __construct(BuildingModel $buildingModel, $data)
    {
        $this->buildingModel = $buildingModel;
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['building_model.' . $this->buildingModel->id];
    }
}
