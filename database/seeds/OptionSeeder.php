<?php

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Option::class, 1)->create([
            'name' => '24"x36" Window',
            'description' => '24"x36" inch vertical slide aluminum window',
            'unit_price' => 150.00,
            'is_active' => 'yes'
            ]);

        factory(Option::class, 1)->create([
            'name' => '48"x72" Heavy Duty Door',
            'description' => '48"x72" heavy duty walk in door',
            'unit_price' => 250.00,
            'is_active' => 'yes'
            ]);

        factory(Option::class, 1)->create([
            'name' => '36"x72" 9 Glass Door',
            'description' => '36"x72" 9-glass steel clad door',
            'unit_price' => 300.00,
            'is_active' => 'yes'
            ]);

        factory(Option::class, 1)->create([
            'name' => 'Extra paint color',
            'description' => 'Extra color (option should be added for every additional color)',
            'unit_price' => 50.00,
            'is_active' => 'yes'
            ]);
    }
}
