<?php

use App\Models\BuildingStatus;
use Illuminate\Database\Seeder;

class BuildingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BuildingStatus::class, 1)->create([
            'id' => 1,
            'type' => 'build',
            'name' => 'Pending',
            'priority' => 1,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 2,
            'type' => 'build',
            'name' => 'Framing',
            'priority' => 2,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 3,
            'type' => 'build',
            'name' => 'Painting',
            'priority' => 3,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 4,
            'type' => 'build',
            'name' => 'Roofing',
            'priority' => 4,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 5,
            'type' => 'delivery',
            'name' => 'Ready to deliver',
            'priority' => 5,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 6,
            'type' => 'delivery',
            'name' => 'Delivering to dealer',
            'priority' => 6,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 7,
            'type' => 'sale',
            'name' => 'Wholesale',
            'priority' => 7,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 8,
            'type' => 'sale',
            'name' => 'Retail',
            'priority' => 8,
            'is_active' => 'yes'
            ]);

        factory(BuildingStatus::class, 1)->create([
            'id' => 9,
            'type' => 'delivery',
            'name' => 'Delivered to customer',
            'priority' => 9,
            'is_active' => 'yes'
            ]);
    }
}
