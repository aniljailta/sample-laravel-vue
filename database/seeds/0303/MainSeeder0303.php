<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MainSeeder0303 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        Model::unguard();

        // Set last building status (status_id = last build status)
        $this->call(BuildingUpdateSeeder0303::class);
        
        // Add dealers (inactived)
        $this->call(DealerSeeder0303::class);
        
        // Generate ORDER and SALES for old buildings (based on CSV (xls from docs))
        // $this->call(BuildingOrderSeeder0303::class);

        Model::reguard();
        Log::info(__CLASS__ . ' End');
    }
}
