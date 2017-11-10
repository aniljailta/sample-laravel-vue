<?php

use Exception as Exception;

use DB as DB;
use Event as Event;
use App\Models\Building;
use App\Models\BuildingHistory;
use App\Events\BuildingHistoryWasAdded;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ChangeStatusSeeder158 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        Model::unguard(false);
        $this->updateEntities();

        Log::info(__CLASS__ . ' End');
    }

    private function updateEntities()
    {
        DB::enableQueryLog();
        $buildings = Building::whereHas('last_location.location', function ($query) {
            $query->where('id', '!=', 1);
        })->whereHas('last_status.building_status', function ($query) {
            $query->where('id', '!=', 4);
        })->get();
        //dd(DB::getQueryLog());

        DB::transaction(function() use($buildings) {
            $buildings->each(function ($building) {
                $buildingHistory = new BuildingHistory();
                $buildingHistory->building_id = $building->id;
                $buildingHistory->status_id = 4;
                $buildingHistory->user_id = null;
                $buildingHistory->save();

                Log::info(__CLASS__ . " Added new status history #{$buildingHistory->id} for building #{$building->id}");
                // Fire building history was added
                Event::fire(new BuildingHistoryWasAdded($buildingHistory));
            });
        });
    }
}
