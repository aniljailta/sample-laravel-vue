<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomerExpectDateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('customer_expects_date', 'ced_end');
            $table->date('order_date')->nullable()->after('dr_notes');
            $table->date('ced_start')->nullable()->after('order_date');
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
            $table->dropColumn('ced_start');
            $table->dropColumn('order_date');
            $table->renameColumn('ced_end', 'customer_expects_date');
        });
    }
}
