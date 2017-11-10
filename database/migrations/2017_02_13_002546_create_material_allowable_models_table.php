<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialAllowableModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_allowable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_model_id')->unsigned()->index();
            $table->integer('material_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('material_id')
                  ->references('id')
                  ->on('materials')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('building_model_id')
                  ->references('id')
                  ->on('building_models')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('material_allowable_models');
    }
}
