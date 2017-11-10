<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_id')->unsigned()->index();
            $table->integer('status_id')->unsigned()->index();
            $table->integer('contractor_id')->unsigned()->index()->nullable();
            $table->double('cost')->nullable();
            $table->string('invoice_number', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('status_id')
                ->references('id')
                ->on('building_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('contractor_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('building_history');
    }
}
