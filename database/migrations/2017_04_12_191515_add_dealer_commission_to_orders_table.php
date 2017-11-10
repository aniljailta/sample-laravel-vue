<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDealerCommissionToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('dealer_tax_rate')->default(0)->nullable()->after('total_sales_price');
            $table->double('dealer_commission_rate')->default(0)->nullable()->after('dealer_tax_rate');
            $table->double('dealer_commission')->default(0)->nullable()->after('dealer_commission_rate');
        });

        // Artisan::call('db:seed', ['--class' => 'OrderUpdateSeeder0304', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('dealer_tax_rate');
            $table->dropColumn('dealer_commission_rate');
            $table->dropColumn('dealer_commission');
        });
    }
}
