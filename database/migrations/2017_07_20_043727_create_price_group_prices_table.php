<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceGroupPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_group_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('price_group_id')->unsigned();
            $table->string('category');
            $table->integer('item_id')->nullable();
            $table->string('item_name')->nullable();
            $table->text('item_description')->nullable();
            $table->double('price', 7, 2)->nullable();
            $table->timestamps();

            $table->foreign('price_group_id')->references('id')->on('price_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_group_prices');
    }
}
