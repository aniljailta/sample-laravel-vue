<?php

namespace App\Observers;

use App\Models\Plant;
use Auth;

class PlantObserver
{
    /**
     * Listen to the Plant created event.
     *
     * @param  Plant  $plant
     * @return void
     */
    public function creating(Plant $plant)
    {
        $select = Plant::query()->selectRaw('MAX(`plant_id`) + 1 as next_plant_id')->firstOrFail();
        $plant->plant_id = $select->next_plant_id ?? 1;
    }

    /**
     * Listen to the Plant updated event.
     *
     * @param  Plant  $plant
     * @return void
     */
    public function updating(Plant $plant)
    {

    }

    /**
     * Listen to the Plant deleting event.
     *
     * @param  Plant  $plant
     * @return void
     */
    public function deleting(Plant $plant)
    {
    }
}