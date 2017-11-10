<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalcColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('total_sales_price')->nullable();
            $table->double('deposit_amount')->nullable();
            $table->double('security_deposit')->nullable();
            $table->double('net_buydown')->nullable();
            $table->double('buydown_tax')->nullable();
            $table->double('balance')->nullable();
            $table->double('rto_amount')->nullable();
            $table->double('rto_advance_monthly_renewal_payment')->nullable();
            $table->double('rto_sales_tax')->nullable();
            $table->double('rto_total_advanceMonthly_renewal_payment')->nullable();
            $table->double('rto_factor')->nullable();
            $table->double('sales_tax')->nullable();
            $table->double('total_amount_due')->nullable();
            $table->double('total_amount')->nullable();
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
            $table->dropColumn('total_sales_price');
            $table->dropColumn('deposit_amount');
            $table->dropColumn('security_deposit');
            $table->dropColumn('net_buydown');
            $table->dropColumn('buydown_tax');
            $table->dropColumn('balance');
            $table->dropColumn('rto_amount');
            $table->dropColumn('rto_advance_monthly_renewal_payment');
            $table->dropColumn('rto_sales_tax');
            $table->dropColumn('rto_total_advanceMonthly_renewal_payment');
            $table->dropColumn('rto_factor');
            $table->dropColumn('sales_tax');
            $table->dropColumn('total_amount_due');
            $table->dropColumn('total_amount');
        });
    }
}
