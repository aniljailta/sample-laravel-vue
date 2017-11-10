<?php

use App\Models\OptionCategory;
use Illuminate\Database\Seeder;

class OptionCategoriesTableSeeder extends Seeder
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
            'name' => 'Siding',
            'group' => 'siding'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
            'sort_id' => 1
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Trim',
            'group' => 'trim'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
            'sort_id' => 2
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Roof',
            'group' => 'roof'
        ], [
            'is_required' => 1,
            'qty_limit' => 1,
            'sort_id' => 3
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Door',
            'group' => 'doors'
        ], [
            'is_required' => 1,
            'sort_id' => 4
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Window',
            'group' => 'windows'
        ], [
            'sort_id' => 5,
        ]);
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Interior',
            'group' => 'interior'
        ], [
            'sort_id' => 6,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Exterior',
            'group' => 'exterior'
        ], [
            'sort_id' => 7,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Electrical',
            'group' => 'misc'
        ], [
            'sort_id' => 8,
        ]);	
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Structural',
            'group' => 'exterior'
        ], [
            'sort_id' => 9,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Insulation',
            'group' => 'interior'
        ], [
            'sort_id' => 10,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Miscellaneous',
            'group' => 'misc'
        ], [
            'sort_id' => 11,
        ]);	
        
        OptionCategory::UpdateOrCreate([
            'name' => 'Permit',
            'group' => 'dealers'
        ], [
            'sort_id' => 12,
        ]);

        OptionCategory::UpdateOrCreate([
            'name' => 'Deck',
            'group' => 'decks'
        ], [
            'sort_id' => 13,
        ]);
      
        Log::info(__CLASS__ . ' End');
    }
}
