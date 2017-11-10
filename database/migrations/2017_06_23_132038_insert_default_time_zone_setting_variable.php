<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultTimeZoneSettingVariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('settings')->where('id', 'time_zone')->first();
        if (!$exists) {
            DB::table('settings')->insert([
                'id' => 'time_zone',
                'title' => 'Time Zone',
                'description' => 'Default Time Zone',
                'value' => 'America/Phoenix',
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
        DB::table('settings')->where('id', 'time_zone')->delete();
    }
}
