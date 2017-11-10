<?php

use App\Models\OptionCategory;
use Illuminate\Database\Seeder;

class OptionCategorySeeder0302 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Door',
            'group' => 'doors'
        ], [
            'is_required' => 1,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Roof',
            'group' => 'roof'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
        ]);
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Trim',
            'group' => 'trim'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
        ]);
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Siding',
            'group' => 'siding'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
        ]);

        Log::info(__CLASS__ . ' End');
    }
}
