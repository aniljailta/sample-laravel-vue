<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coloring', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('colorable_id')->unsigned()->index();
            $table->string('colorable_type')->index();
            $table->integer('color_id')->nullable()->unsigned()->index();
            $table->string('type')->nullable()->index();
            $table->string('custom')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
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
        Schema::drop('coloring');
    }
}
