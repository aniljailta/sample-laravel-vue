<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable()->index();
            $table->string('name');
            $table->string('hex')->nullable();
            $table->string('url')->nullable();
            $table->integer('option_id')->unsigned()->nullable()->index();
            $table->tinyInteger('use_body')->index();
            $table->tinyInteger('use_trim')->index();
            $table->tinyInteger('use_shingle')->index();

            $table->timestamps();
            $table->softDeletes();

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
        Schema::drop('colors');
    }
}
