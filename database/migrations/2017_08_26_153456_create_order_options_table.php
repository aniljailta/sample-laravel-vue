<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('option_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price', 7, 2)->unsigned();
            $table->decimal('total_price', 7, 2)->unsigned();
            $table->timestamps();
        });

        Schema::table('order_options', function (Blueprint $table) {
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('option_id')
                ->references('id')->on('options')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_options', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['option_id']);
        });

        Schema::dropIfExists('order_options');
    }
}
