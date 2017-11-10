<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionPackageOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_package_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_package_id')->unsigned()->index();
            $table->integer('option_id')->unsigned()->index();
            $table->double('unit_price');

            $table->timestamps();

            $table->foreign('option_package_id')
                ->references('id')
                ->on('option_packages')
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
        Schema::drop('option_package_options');
    }
}
