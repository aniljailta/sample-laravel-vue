<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('priority')->unsigned()->index();
            $table->enum('is_active', ['yes', 'no'])->index();
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
        Schema::drop('building_statuses');
    }
}
