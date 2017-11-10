<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForceQuantityInOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE `options` CHANGE `force_quantity` `force_quantity` ENUM('building_length','wall_area','floor_area')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE `options` CHANGE `force_quantity` `force_quantity` ENUM('building_length')");
            DB::statement("UPDATE `options` SET `force_quantity` = NULL WHERE force_quantity NOT IN ('building_length')");
        });
    }
}
