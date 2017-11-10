<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBuildDateColumnFromBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::table('buildings', function (Blueprint $table) {
            $table->date('build_date')->nullable()->change();
        }); */
        DB::statement('ALTER TABLE `buildings` MODIFY `build_date` DATE NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Schema::table('buildings', function (Blueprint $table) {
            $table->date('build_date')->nullable(false)->change();
        });*/
        DB::statement('ALTER TABLE `buildings` MODIFY `build_date` DATE NOT NULL');
    }
}
