<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorAllowableModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_allowable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_model_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->timestamps();
            
            $table->foreign('color_id')
                  ->references('id')
                  ->on('colors')
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
        Schema::drop('color_allowable_models');
    }
}
