<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingPackageOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_package_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_package_id')->unsigned()->index();
            $table->integer('option_id')->unsigned()->index();
            $table->double('unit_price')->nullable();
            $table->integer('quantity')->unsigned()->default(0);

            $table->timestamps();

            $table->foreign('building_package_id')
                ->references('id')
                ->on('building_packages')
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
        Schema::drop('building_package_options');
    }
}
