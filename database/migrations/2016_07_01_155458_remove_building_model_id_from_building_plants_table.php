<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBuildingModelIdFromBuildingPlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_plants', function (Blueprint $table) {
            $table->dropForeign('building_plants_building_model_id_foreign');
            $table->dropColumn('building_model_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_plants', function (Blueprint $table) {
            $table->integer('building_model_id')->unsigned()->index();
            $table->foreign('building_model_id')
                ->references('id')
                ->on('building_models')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
