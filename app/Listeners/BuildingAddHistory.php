<?php

namespace App\Listeners;

use App\Models\Building;
use App\Models\User;
use App\Models\BuildingStatus;
use App\Models\BuildingHistory;
use App\Events\BuildingWasAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuildingAddHistory
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BuildingWasAdded|BuildingWasUpdated $event
     */
    public function handle(BuildingWasAdded $event)
    {
        // Update history
        // Get min priority per each status type
        $subQueryP = \DB::table('building_statuses as table_priorities')->selectRaw('MIN(priority)')
            ->whereRaw('table_priorities.type = table_ids.type');

        if ($event->building->default_status) {
            $subQueryP->whereRaw("table_priorities.name = '" . $event->building->default_status . "'");
        }

        $subQueryI = \DB::table('building_statuses AS table_ids')
            ->selectRaw('MAX(id)')
            ->where('table_ids.priority', \DB::raw(' ( ' . $subQueryP->toSql() . ' )'))
            ->groupBy('type');

        $nextBuildingStatuses = BuildingStatus::whereIn('id', function($query) use(&$subQueryI) {
            $query->from(\DB::raw(' ( ' . $subQueryI->toSql() . ' ) as table_ids'));
        });

        $nextBuildingStatuses = $nextBuildingStatuses->get();
        $nextBuildingStatuses->each(function($buildingStatus) use ($event) {
            $buildingHistory = new BuildingHistory([
                'building_id' => $event->building->id,
                'status_id' => $buildingStatus->id
            ]);

            if ($event->user) {
                $buildingHistory->user_id = $event->user->id;
            }

            $buildingHistory = $event->building->building_history()->save($buildingHistory);
            $event->building->update(['status_id' => $buildingHistory->id]);
        });
    }
}
