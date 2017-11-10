<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_building_serial')->index();
            $table->longText('inventory_building_description');
            $table->double('inventory_building_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventory_buildings');
    }
}
