<?php

use App\Models\Style;
use Illuminate\Database\Seeder;

class StyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Style::class, 1)->create([
            'name' => 'Mini Barn',
            'description' => 'Gambrel roof over a 4\' sidewall',
            'short_code' => 'MIBA',
            'is_active' => 'yes'
            ]);

        factory(Style::class, 1)->create([
            'name' => 'Urban Shack',
            'description' => 'Standard peak style roof over 8\' sidewalls',
            'short_code' => 'URSH',
            'is_active' => 'yes'
            ]);

        factory(Style::class, 1)->create([
            'name' => 'Urban Barn',
            'description' => 'Gambrel style roof over 7\' sidewall',
            'short_code' => 'URBA',
            'is_active' => 'yes'
            ]);

        factory(Style::class, 1)->create([
            'name' => 'Urban Lean-To',
            'description' => 'Single slope roof over 8\' sidewall',
            'short_code' => 'URLT',
            'is_active' => 'yes'
            ]);
    }
}
