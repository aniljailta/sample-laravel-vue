<?php

use App\Models\Dealer;
use Illuminate\Database\Seeder;

class DealerSeeder0303 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');

        Dealer::firstOrCreate(['business_name' => 'D&H', 'is_active' => 'no']);
        Dealer::firstOrCreate(['business_name' => 'D&H Enterprises', 'is_active' => 'no']);
        Dealer::firstOrCreate(['business_name' => 'Devry', 'is_active' => 'no']);
        Dealer::firstOrCreate(['business_name' => 'Furniture King', 'is_active' => 'no']);
        Dealer::firstOrCreate(['business_name' => 'Loumer', 'is_active' => 'no']);

        Log::info(__CLASS__ . ' End');
    }
}
