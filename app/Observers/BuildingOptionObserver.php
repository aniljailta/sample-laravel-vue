<?php

namespace App\Observers;

use App\Models\BuildingOption;

class BuildingOptionObserver
{
    /**
     * Listen to the Building Option created event.
     *
     * @param  BuildingOption  $buildingOption
     * @return void
     */
    public function created(BuildingOption $buildingOption)
    {
        // $buildingOption->building->total_options += $buildingOption->unit_price;
        // $buildingOption->building->save();
    }

    /**
     * Listen to the Building Option updated event.
     *
     * @param  BuildingOption  $buildingOption
     * @return void
     */
    public function updating(BuildingOption $buildingOption)
    {

    }

    /**
     * Listen to the Building deleting event.
     *
     * @param  BuildingOption  $buildingOption
     * @return void
     */
    public function deleted(BuildingOption $buildingOption)
    {
        // $buildingOption->building->total_options -= $buildingOption->unit_price;
        // $buildingOption->building->save();
    }
}