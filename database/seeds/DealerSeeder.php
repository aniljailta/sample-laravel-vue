<?php

use App\Models\Dealer;
use Illuminate\Database\Seeder;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usc1 = factory(Dealer::class, 1)->create([
            'business_name' => 'USC (Plant 1)',
            'phone' => '602.320.8350',
            'email' => 'info@urbanshedconcepts.com',
            'tax_rate' => 10,
            'cash_sale_deposit_rate' => 6,
            'location_id' => 1
            ]);

        $usc2 = factory(Dealer::class, 1)->create([
            'business_name' => 'USC (Bell Road Retail)',
            'phone' => '85022 602.579.1590',
            'email' => 'info@urbanshedconcepts.com',
            'tax_rate' => 10,
            'cash_sale_deposit_rate' => 6,
            'location_id' => 2
            ]);

        $fourSeasons = factory(Dealer::class, 1)->create([
            'business_name' => '4 Seasons Motor Sports',
            'phone' => '(928) 474-3411',
            'email' => 'fourseasons_rv@yahoo.com',
            'tax_rate' => 10,
            'cash_sale_deposit_rate' => 6,
            'location_id' => 3
            ]);

        $countryTrailer = factory(Dealer::class, 1)->create([
            'business_name' => 'A Country Trailer',
            'phone' => '(928) 636-6995',
            'email' => 'shasta6995@commspeed.net',
            'tax_rate' => 10,
            'cash_sale_deposit_rate' => 6,
            'location_id' => 4
            ]);
        
        $loumers = factory(Dealer::class, 1)->create([
            'business_name' => 'LouMers',
            'phone' => '602.312.5534',
            'email' => 'luisacabrillas@gmail.com',
            'tax_rate' => 10,
            'cash_sale_deposit_rate' => 6,
            'location_id' => 5
            ]);

    }
}
