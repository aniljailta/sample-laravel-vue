<?php

namespace App\Listeners;

use App\Events\BuildingWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingTotalRecalculate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BuildingWasUpdated  $event
     * @return void
     */
    public function handle(BuildingWasUpdated $event)
    {
        // Update totals
        $event->building->total_options = $event->building->building_options()->sum('total_price');
        $event->building->total_price = $event->building->shell_price + $event->building->total_options;
        $event->building->save();
    }
}
