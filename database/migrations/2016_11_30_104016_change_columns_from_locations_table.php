<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsFromLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('locations', function (Blueprint $table) {
            $table->double('latitude')->nullable()->change();
            $table->double('longitude')->nullable()->change();
        });*/ 
        DB::statement('ALTER TABLE `locations` MODIFY `latitude` DOUBLE NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `locations` MODIFY `longitude` DOUBLE NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('locations', function (Blueprint $table) {
            $table->double('latitude')->nullable(false)->change();
            $table->double('longitude')->nullable(false)->change();
        });*/
        DB::statement('ALTER TABLE `locations` MODIFY `latitude` DOUBLE NOT NULL');
        DB::statement('ALTER TABLE `locations` MODIFY `longitude` DOUBLE NOT NULL');
    }
}
