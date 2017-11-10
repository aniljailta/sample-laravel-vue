<?php
use Illuminate\Database\Seeder;

class FeeTypesSeeder extends Seeder
{
    const TYPE = 'type';
    const SLUG = 'slug';
    const NAME = 'name';
    const DESCRIPTION = 'description';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('fee_types')->truncate();

        DB::table('fee_types')->insert([
            [
                static::TYPE => 'monthly_subscription',
                static::SLUG => 'base',
                static::NAME => 'Base Subscription',
                static::DESCRIPTION => 'Basic monthly subscription fee',
            ],
            [
                static::TYPE => 'per_transaction',
                static::SLUG => 'per_sale',
                static::NAME => 'Per Sale',
                static::DESCRIPTION => 'Fee per sale',
            ],
            [
                static::TYPE => 'monthly_subscription',
                static::SLUG => '3d_subscription',
                static::NAME => '3D Subscription',
                static::DESCRIPTION => '3D monthly subscription fees',
            ],
            [
                static::TYPE => 'per_transaction',
                static::SLUG => 'payment_processing',
                static::NAME => 'Payment processing',
                static::DESCRIPTION => 'Pay Now payment processing',
            ],
            [
                static::TYPE => 'setup',
                static::SLUG => 'onboarding_fee_base',
                static::NAME => 'Onboarding fee - base subscription',
                static::DESCRIPTION => 'One time base subscription onboarding fee',
            ],
            [
                static::TYPE => 'setup',
                static::SLUG => 'onboarding_fee_3d',
                static::NAME => 'Onboarding fee - 3D subscription',
                static::DESCRIPTION => 'One time 3D subscription onboarding fee',
            ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
