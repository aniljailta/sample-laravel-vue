<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullableLocationIdInDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `dealers` CHANGE `location_id` `location_id` INT(10) UNSIGNED NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement('ALTER TABLE `dealers` CHANGE `location_id` `location_id` INT(10) UNSIGNED NOT NULL');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
