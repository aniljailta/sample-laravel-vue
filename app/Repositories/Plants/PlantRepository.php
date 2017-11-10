<?php

namespace App\Repositories\Plants;

use App\Models\Plant;
use App\Repositories\BaseRepository;

class PlantRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Plant::class;

    /**
     * PlantRepository constructor.
     */
    public function __construct()
    {
    }


    /**
     * Get default (initial) plant for new building
     * @return Plant
     */
    public function getDefaultPlant(): Plant {
        return Plant::firstOrFail();
    }
}