<?php

use App\Models\Option;
use App\Models\Building;
use App\Models\BuildingHistory;
use App\Models\Order;

use Illuminate\Database\Seeder;

class BuildingUpdateSeeder0303 extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        $this->updateBuildings();

        Log::info(__CLASS__ . ' End');
    }

    /**
     * Update all building statuses
     */
    private function updateBuildings() {        
        $buildings = Building::withTrashed()->get();
        $buildings->load(['building_history']);
        Log::info(__CLASS__ . " == Total buildings: {$buildings->count()}");

        foreach ($buildings as $building) {
            DB::transaction(function() use($building) {
                $this->updateBuilding($building);
            });
        }
    }

    private function updateBuilding($building) {
        $lastStatus = BuildingHistory::where('building_id', $building->id)->withTrashed()->get()->last();
        if ($lastStatus) {
            $building->status_id = $lastStatus->id;
        } else {
            Log::info(__CLASS__ . " == Building #{$building->id} has no building status!");
        }
        
        $building->save();
        
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        //$buildingColors = DB::table('coloring')->where('colorable_type', 'building')->where('colorable_id', $building->id)->get();
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        
        Log::info(__CLASS__ . " == Update building #{$building->id}");
    }
}
