<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MainSeeder0302 extends Seeder
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

        $this->call(MaterialSeeder0302::class);

        // Drop statused !== build and add new inital status = draft
        $this->call(BuildingStatusesDeleteSeeder0302::class);

        $this->call(OptionCategorySeeder0302::class);
        $this->call(OptionSeeder0302::class);
        $this->call(BuildingSeeder0302::class);
        $this->call(BuildingUpdateSeeder0302::class);

        Model::reguard();
        Log::info(__CLASS__ . ' End');
    }
}
