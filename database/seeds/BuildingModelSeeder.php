<?php

use App\Models\Style;
use App\Models\BuildingModel;
use Illuminate\Database\Seeder;

class BuildingModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BuildingModel::class, 1)->create([
            'style_id' => 1,
            'name' => 'Mini Barn',
            'description' => NULL,
            'width' => 8,
            'wall_height' => 12,
            'length' => 6,
            'shell_price' => 1200,
            'is_active' => 'yes'
            ]);

        factory(BuildingModel::class, 1)->create([
            'style_id' => 2,
            'name' => 'Urban Shack',
            'description' => NULL,
            'width' => 10,
            'wall_height' => 13,
            'length' => 4,
            'shell_price' => 1150,
            'is_active' => 'yes'
            ]);

        factory(BuildingModel::class, 1)->create([
            'style_id' => 3,
            'name' => 'Backyard Barn',
            'description' => NULL,
            'width' => 18,
            'wall_height' => 10,
            'length' => 12,
            'shell_price' => 1340,
            'is_active' => 'yes'
            ]);
    }
}
