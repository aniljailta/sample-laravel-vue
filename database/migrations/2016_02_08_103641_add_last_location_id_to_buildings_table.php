<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastLocationIdToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {

            $table->integer('last_location_id')->unsigned()->index()->nullable()->after('last_history_id');
            $table->foreign('last_location_id')
                ->references('id')
                ->on('building_locations')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {

            $table->dropForeign('buildings_last_location_id_foreign');
            $table->dropColumn('last_location_id');

        });
    }
}
