<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialAllowableColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_allowable_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('color_id')
                  ->references('id')
                  ->on('colors')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('material_id')
                  ->references('id')
                  ->on('materials')
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
        Schema::drop('material_allowable_colors');
    }
}
