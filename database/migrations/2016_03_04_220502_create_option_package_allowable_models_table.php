<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionPackageAllowableModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_package_allowable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_package_id')->unsigned()->index();
            $table->integer('building_model_id')->unsigned()->index();

            $table->timestamps();

            $table->foreign('option_package_id')
                ->references('id')
                ->on('option_packages')
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
        Schema::drop('option_package_allowable_models');
    }
}
