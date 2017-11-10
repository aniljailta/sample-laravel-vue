<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDriverFromBuildingLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_locations', function (Blueprint $table) {
            $table->dropForeign('building_locations_driver_id_foreign');
            $table->dropColumn('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_locations', function (Blueprint $table) {
            $table->integer('driver_id')->unsigned()->nullable()->index();
            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
