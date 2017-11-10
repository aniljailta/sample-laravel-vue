<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropModelBuildingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('model_building_statuses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('model_building_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->unsigned()->index();
            $table->integer('status_id')->unsigned()->index();
            $table->double('cost')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('model_id')
                ->references('id')
                ->on('building_models')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('status_id')
                ->references('id')
                ->on('building_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
