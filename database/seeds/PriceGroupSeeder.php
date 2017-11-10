<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PriceGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\PriceGroup::class)->create([
            'name'         => 'Price Group 1',
            'category'     => 'models',
            'status'       => 'pending',
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now(),
            'publish_date' => \Carbon\Carbon::now()
        ]);

        factory(\App\Models\PriceGroup::class)->create([
            'name'         => 'Price Group 2',
            'category'     => 'options',
            'status'       => 'pending',
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now(),
            'publish_date' => \Carbon\Carbon::now()
        ]);

        factory(\App\Models\PriceGroup::class)->create([
            'name'         => 'Price Group 3',
            'category'     => 'models',
            'status'       => 'published',
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now(),
            'published_at' => \Carbon\Carbon::now()
        ]);

        factory(\App\Models\PriceGroup::class)->create([
            'name'         => 'Price Group 4',
            'category'     => 'options',
            'status'       => 'published',
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now(),
            'published_at' => \Carbon\Carbon::now()
        ]);

        factory(\App\Models\PriceGroup::class)->create([
            'name'       => 'Price Group 5',
            'category'   => 'options',
            'status'     => 'draft',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}
