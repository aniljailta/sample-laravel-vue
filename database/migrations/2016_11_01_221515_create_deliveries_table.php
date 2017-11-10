<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_id')->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('building_id')->unsigned()->index();
            // $table->integer('sale_id')->unsigned()->nullable()->index(); // later
            $table->integer('location_start_id')->unsigned()->nullable()->index();
            $table->integer('location_end_id')->unsigned()->nullable()->index();
            $table->integer('length')->unsigned()->nullable();
            $table->double('price')->nullable();
            $table->double('cost')->nullable();
            $table->string('invoice')->nullable();
            $table->text('notes')->nullable();
            $table->date('ready_date')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('building_id')
                  ->references('id')
                  ->on('buildings')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('location_start_id')
                  ->references('id')
                  ->on('locations')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('location_end_id')
                  ->references('id')
                  ->on('locations')
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
        Schema::drop('deliveries');
    }
}
