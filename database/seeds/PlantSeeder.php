<?php

use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plant::create([
            'name' => 'USC HQ',
            'description' => 'USC Main Manufacturing Plant',
            'location_id' => 1
            ]);
    }
}
