<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('style_id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->string('short_code', 65);
            $table->enum('is_active', ['yes', 'no'])->index();
            $table->timestamps();

            $table->foreign('style_id')
                  ->references('id')
                  ->on('styles')
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
        Schema::drop('building_models');
    }
}
