<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullableFieldsInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            DB::statement('ALTER TABLE `locations` CHANGE `name` `name` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `address` `address` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `country` `country` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `city` `city` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `state` `state` VARCHAR(255) NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `zip` `zip` VARCHAR(255) NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            DB::statement('ALTER TABLE `locations` CHANGE `name` `name` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `address` `address` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `country` `country` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `city` `city` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `state` `state` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `locations` CHANGE `zip` `zip` VARCHAR(255) NOT NULL');
        });
    }
}
