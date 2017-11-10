<?php

use App\Models\Option;
use App\Models\Material;
use App\Models\Color;
use App\Models\OptionCategory;
use App\Models\BuildingModel;
use Illuminate\Database\Seeder;

class OptionSeeder0302 extends Seeder
{
    protected $buildingModelsID;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');

        DB::transaction(function() {
            $this->createOptions();
        });

        Log::info(__CLASS__ . ' End');
    }

    private function createOptions() {
        $this->buildingModelsID  = $this->getBuildingModels();
        $roofCategory = OptionCategory::where('group', 'roof')->first();
        $trimCategory = OptionCategory::where('group', 'trim')->first();
        $sidingCategory = OptionCategory::where('group', 'siding')->first();

        $metal = Material::where('name', '29-gage Metal')->first();
        $shingle = Material::where('name', '30-year Asphalt Dimensional Shingle')->first();
        $lpSmartside = Material::where('name', 'LP Smartside')->first();
        $lpSmarttrim = Material::where('name', 'LP 440 Smarttrim')->first();
        
        if ($roofCategory) {
            // OLD
            $this->createOption([
                'name' => "Shingle Roof 1' (lf) (prices included in building model)",
                'category_id' => $roofCategory->id,
                'force_quantity' => 'building_length',
                'unit_price' => 0,
                'material_id' => $shingle->id,
                'is_active' => 'yes'
            ], 'roof');

            // NEW
            $this->createOption([
                'name' => "Metalic Roof 1' (lf)",
                'category_id' => $roofCategory->id,
                'force_quantity' => 'building_length',
                'unit_price' => 0,
                'material_id' => $metal->id,
                'is_active' => 'yes'
            ], 'roof');
        }

        if ($trimCategory) {
            $this->createOption([
                'name' => "LP Smartside Trim 1' (lf) (prices included in building model)",
                'category_id' => $trimCategory->id,
                'force_quantity' => 'building_length',
                'unit_price' => 0,
                'material_id' => $lpSmarttrim->id,
                'is_active' => 'yes'
            ], 'trim');
        }

        if ($sidingCategory) {
            $this->createOption([
                'name' => "LP Smartside Siding 1' (lf) (prices included in building model)",
                'category_id' => $sidingCategory->id,
                'force_quantity' => 'building_length',
                'unit_price' => 0,
                'material_id' => $lpSmartside->id,
                'is_active' => 'yes'
            ], 'body');
        }
    }

    private function createOption($attributes, $use) {
        $option = Option::firstOrCreate($attributes);
        if ($option) {
            $colorID  = $this->getColors($use);
            $option->allowable_colors()->sync($colorID);
            $option->allowable_models()->sync($this->buildingModelsID);
        }
        return $option;
    }

    private function getColors($use) {
        $usage = "use_{$use}";
        $colorID = Color::where($usage, 1)->pluck('id');
        return $colorID;
    }

    private function getBuildingModels() {
        $models = BuildingModel::all()->pluck('id');
        return $models;
    }
}
