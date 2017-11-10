<?php

use App\Services\Building\BuildingService;
use App\Models\Option;
use App\Models\Building;
use App\Models\BuildingHistory;
use App\Models\BuildingStatus;
use App\Models\Order;
use App\Events\BuildingWasUpdated;

use Illuminate\Database\Seeder;

class BuildingUpdateSeeder0302 extends Seeder
{
    
    protected $newOptions;

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
     * Update all building (building options). Add new options (roof, trim, siding) and colors
     */
    private function updateBuildings() {
        $this->newOptions = $this->getNewOptions();
        if (count($this->newOptions) !== 3) {
            Log::info(__CLASS__ . ' == New building options !== 3; Return');
            return;
        }
        
        $buildings = Building::withTrashed()->get();
        $buildings->load([
            'building_history', 
            'building_options', 
            'building_options.colors'
        ]);
        Log::info(__CLASS__ . " == Total buildings: {$buildings->count()}");

        foreach ($buildings as $building) {
            DB::transaction(function() use($building) {
                $this->updateBuilding($building);
            });
        }
    }

    private function updateBuilding($building) {
        // associate buldings with orders (if exists)
        if (!$building->order_id) {
            $order = Order::where('building_id', $building->id)->get()->last();
            if ($order) {
                $building->order_id = $order->id;

                Log::info(__CLASS__ . " == Associate building #{$building->id} <--> order #{$order->id}");
            }
        }

        /*
        $status = BuildingStatus::where('name', 'Ready to Deliver')->first();
        // approve buildings with serial numbers
        if ($building->serial_number && $status) {
            // $building->status_id = 'approved';
            $buildingHistory = BuildingHistory::create([
                'building_id' => $building->id,
                'status_id' => $status->id
            ]);

            $building->status_id = $buildingHistory->id;
        } else {
            Log::info(__CLASS__ . " == Building #{$building->id} has no serial number");
        }*/
        
        $building->save();
        
        if (!$building->building_model_id) {
            Log::info(__CLASS__ . " == Building #{$building->id} has no building model; Return");
            return;
        }
        
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $buildingColors = DB::table('coloring')->where('colorable_type', 'building')->where('colorable_id', $building->id)->get();
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        $buildingOptions = collect($building->building_options) ?: collect();
        
        foreach ($this->newOptions as $type => $option) {
            $color = $buildingColors->where('type', $type)->first();
            if ($color) {
                $newColor = [];
                $newColor['id'] = $color['color_id'];
                $newColor['name'] = $color['custom'] ?: null;
                
                $newBuildingOption = [];
                $newBuildingOption['option_id'] = $option['id'];
                $newBuildingOption['unit_price'] = $option['unit_price'];
                $newBuildingOption['quantity'] = 1;
                if ($option->force_quantity === 'building_length') {
                    $newBuildingOption['quantity'] = $building->length;
                }

                $newBuildingOption['color'] = $newColor;

                $oldBoIndex = $building->building_options->search(function($bo) use($newBuildingOption) {
                   return $bo->option_id === $newBuildingOption['option_id'];
                });

                if ($oldBoIndex !== false) {
                    $buildingOptions->forget($oldBoIndex);
                }

                $buildingOptions->push($newBuildingOption);
            }
        }
        
        $buildingService = new BuildingService();
        $buildingParams['options'] = $buildingOptions->toArray();
        $buildingService->saveOptions($building, $buildingParams);
        // do not recalculate total prices
        // Event::fire(new BuildingWasUpdated($building));
        
        Log::info(__CLASS__ . " == Update building options #{$building->id}");
    }

    private function getNewOptions() {
        $options = [];
        $options['roof'] = Option::where('name', "Shingle Roof 1' (lf) (prices included in building model)")->first();
        $options['trim'] = Option::where('name', "LP Smartside Trim 1' (lf) (prices included in building model)")->first();
        $options['body'] = Option::where('name', "LP Smartside Siding 1' (lf) (prices included in building model)")->first();
        return $options;
    }
}
