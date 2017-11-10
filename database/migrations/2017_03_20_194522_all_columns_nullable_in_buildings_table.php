<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllColumnsNullableInBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `buildings` CHANGE `plant_id` `plant_id` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `building_model_id` `building_model_id` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `serial_number` `serial_number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `width` `width` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `height` `height` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `length` `length` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `shell_price` `shell_price` DOUBLE NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `total_options` `total_options` DOUBLE NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `total_price` `total_price` DOUBLE NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement('ALTER TABLE `buildings` CHANGE `plant_id` `plant_id` INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `building_model_id` `building_model_id` INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `serial_number` `serial_number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `width` `width` INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `height` `height` INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `length` `length` INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `shell_price` `shell_price` DOUBLE NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `total_options` `total_options` DOUBLE NOT NULL');
        DB::statement('ALTER TABLE `buildings` CHANGE `total_price` `total_price` DOUBLE NOT NULL;');
        DB::statement('SET foreign_key_checks = 1;');
    }
}
