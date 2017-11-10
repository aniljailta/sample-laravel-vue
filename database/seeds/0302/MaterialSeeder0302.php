<?php

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder0302 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        Material::firstOrCreate(['name' => '29-gage Metal']);
        Material::firstOrCreate(['name' => '30-year Asphalt Dimensional Shingle']);
        Material::firstOrCreate(['name' => 'LP Smartside']);
        Material::firstOrCreate(['name' => 'LP 440 Smarttrim']);
        Material::firstOrCreate(['name' => 'Aluminum']);

        Log::info(__CLASS__ . ' End');
    }
}
