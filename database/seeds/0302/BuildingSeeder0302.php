<?php

use App\Services\Building\BuildingService;

use App\Models\Order;
use App\Models\Building;
use App\Models\BuildingModel;
use Illuminate\Database\Seeder;

class BuildingSeeder0302 extends Seeder
{
    protected $buildingModels;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        Building::unguard(false);
        $this->createBuildings();

        Log::info(__CLASS__ . ' End');
    }

    /**
     * Create building only for order without buildings
     */
    private function createBuildings() {
        $this->buildingModels = BuildingModel::withTrashed()->get();
        $ordersWithoutBuildings = DB::table('orders')->whereNull('building_id')->get();
        foreach ($ordersWithoutBuildings as $order) {
            DB::transaction(function() use($order) {
                $this->createBuilding($order);
            });
        }
    }

    /**
     * Method creates the new building for order.
     * Converting order-building attributes to building 
     * @param $order
     */
    private function createBuilding($order) {
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $orderOptions = DB::table('order_building_options')->where('order_id', $order->id)->select(['option_id', 'quantity', 'unit_price'])->get();
        $orderColors = DB::table('coloring')->where('colorable_type', 'order')->where('colorable_id', $order->id)->get();
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        
        $buildingService = new BuildingService();
        $buildingParams = [];
        // $buildingParams['status_id'] = 'draft';
        $buildingParams['order_id'] = $order->id;
        $buildingParams['options'] = $orderOptions->toArray();
        $buildingParams['notes'] = 'auto-migrate';
        
        if ($order->building_model_id) {
            $buildingModel = $this->buildingModels->where('id', $order->building_model_id)->last();
            if ($buildingModel) {
                $buildingParams['building_model_id'] = $buildingModel->id;
                $buildingParams['width'] = $buildingModel->width;
                $buildingParams['height'] = $buildingModel->wall_height;
                $buildingParams['length'] = $buildingModel->length;
                $buildingParams['shell_price'] = $buildingModel->shell_price;
            }
        }

        // create building
        $building = $buildingService->create($buildingParams);
        if ($building) {
            $orderColors = $orderColors->toArray();
            foreach ($orderColors as &$color) {
                $color['colorable_id'] = $building->id;
                $color['colorable_type'] = $building->getMorphClass();
                unset($color['id']);
                
                DB::table('coloring')->insert($color);
            }
            
            Order::where('id', $order->id)->update(['building_id' => $building->id]);
            Log::info(__CLASS__ . " == Create new building #{$building->id} | order #{$order->id}");
        }
    }
}
