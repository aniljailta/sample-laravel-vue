<?php

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Location::class, 1)->create([
            'id' => 1,
            'name' => 'USC (Plant 1)',
            'address' => '310 S 43rd Ave',
            'country' => 'United States',
            'state' => 'AZ',
            'city' => 'Phoenix',
            'zip' => '85009',
            'latitude' => '33.4447802',
            'longitude' => '-112.1551605',
            'is_geocoded' => 'yes',
        ]);
        factory(Location::class, 1)->create([
            'id' => 2,
            'name' => "USC (Bell Road Retail)",
            'address' => '1675 E Bell Road',
            'country' => 'United States',
            'state' => 'AZ',
            'city' => 'Phoenix',
            'zip' => '85022',
            'latitude' => '33.6403437',
            'longitude' => '-112.0484529',
            'is_geocoded' => 'yes',
        ]);
        factory(Location::class, 1)->create([
            'id' => 3,
            'name' => "4 Seasons Motor Sports",
            'address' => '16458 N AZ Hwy 87',
            'country' => 'United States',
            'state' => 'AZ',
            'city' => 'Payson',
            'zip' => ' 85541',
            'latitude' => '34.1047486',
            'longitude' => '-111.3566748',
            'is_geocoded' => 'yes',
        ]);
        factory(Location::class, 1)->create([
            'id' => 4,
            'name' => "A Country Trailer",
            'address' => '3890 AZ-89',
            'country' => 'United States',
            'state' => 'AZ',
            'city' => 'Chino Valley',
            'zip' => ' 86323',
            'latitude' => '34.8029237',
            'longitude' => '-112.4532229',
            'is_geocoded' => 'yes',
        ]);
        factory(Location::class, 1)->create([
            'id' => 5,
            'name' => "LouMers",
            'address' => '1013 N 13th St',
            'country' => 'United States',
            'state' => 'AZ',
            'city' => 'Phoenix',
            'zip' => ' 85006',
            'latitude' => '33.4591208',
            'longitude' => '-112.0562011',
            'is_geocoded' => 'yes',
        ]);

    }
}
