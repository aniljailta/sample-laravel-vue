<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionAllowableColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_allowable_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('color_id')
                  ->references('id')
                  ->on('colors')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('option_id')
                  ->references('id')
                  ->on('options')
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
        Schema::drop('option_allowable_colors');
    }
}
