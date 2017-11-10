<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultEstimatedDeliveryPeriodVariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('settings')->where('id', 'estimated_delivery_period')->first();
        if (!$exists) {
            DB::table('settings')->insert([
                'id' => 'estimated_delivery_period',
                'title' => 'Estimated Delivery Period ',
                'description' => 'Estimated Delivery Period',
                'value' => '5',
                'created_at' =>  \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('id', 'estimated_delivery_period')->delete();
    }
}
