<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSortidPlantYearOnBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('sort_id');
            $table->integer('manufacture_year');
        });

        DB::statement("UPDATE `buildings` SET `sort_id` = CONCAT(SUBSTRING(serial_number, 13, 3), SUBSTRING(serial_number, -2), SUBSTRING(serial_number, -6, 4)), `manufacture_year` = CONCAT('20', SUBSTRING(serial_number, -2))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('sort_id');
            $table->dropColumn('manufacture_year');
        });
    }
}
