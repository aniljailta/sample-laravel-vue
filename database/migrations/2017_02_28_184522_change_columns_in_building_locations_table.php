<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsInBuildingLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `building_locations` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement('ALTER TABLE `building_locations` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL;');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
