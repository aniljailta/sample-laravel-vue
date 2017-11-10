<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPricesFieldsInOrderOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_options', function (Blueprint $table) {
            $table->decimal('unit_price', 7, 2)->unsigned(false)->change();
            $table->decimal('total_price', 7, 2)->unsigned(false)->change();
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
            $table->decimal('unit_price', 7, 2)->unsigned(true)->change();
            $table->decimal('total_price', 7, 2)->unsigned(true)->change();
        });
    }
}
