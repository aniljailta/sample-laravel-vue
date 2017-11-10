<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plant_id')->unsigned()->index();
            $table->integer('building_model_id')->unsigned()->index();
            $table->string('serial_number')->index();
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('length')->unsigned();
            $table->date('build_date');
            $table->string('body_color');
            $table->string('trim_color');
            $table->string('third_color');
            $table->string('shingle_color');
            $table->double('shell_price');
            $table->double('total_options');
            $table->double('total_price');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
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
        Schema::drop('buildings');
    }
}
