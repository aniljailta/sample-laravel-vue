<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFieldsFromBuildingModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_models', function (Blueprint $table) {
            $table->dropForeign('building_models_style_id_foreign');
            $table->dropColumn('style_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_models', function (Blueprint $table) {
            $table->integer('style_id')->unsigned()->index()->nullable();
            $table->foreign('style_id')
                  ->references('id')
                  ->on('styles')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
}
