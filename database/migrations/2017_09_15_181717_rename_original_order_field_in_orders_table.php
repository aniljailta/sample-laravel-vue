<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOriginalOrderFieldInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropForeign('orders_original_order_foreign');
            DB::statement('ALTER TABLE `orders` CHANGE COLUMN `original_order` `original_order_id` INT(10) UNSIGNED NULL');
            // $table->foreign('original_order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropForeign('orders_original_order_id_foreign');
            DB::statement('ALTER TABLE `orders` CHANGE COLUMN `original_order_id` `original_order` INT(10) UNSIGNED NULL');
            // $table->foreign('original_order')->references('id')->on('orders');
        });
    }
}
