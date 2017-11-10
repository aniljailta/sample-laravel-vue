<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalculatedFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement('ALTER TABLE `orders` CHANGE `deposit_received` `amount_received` DOUBLE NULL DEFAULT NULL;');
            $table->double('mf_deposit_received', 7, 2)->nullable()->after('amount_received');
            $table->double('rto_deposit_received', 7, 2)->nullable()->after('mf_deposit_received');
            $table->double('mf_amount_due', 7, 2)->nullable()->after('rto_deposit_received');
            $table->double('rto_amount_due', 7, 2)->nullable()->after('mf_amount_due');
            $table->double('total_amount_due', 7, 2)->nullable()->after('rto_amount_due');
            $table->double('cash_deposit_amount', 7, 2)->nullable()->after('total_amount_due');
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
            $table->dropColumn('mf_deposit_received');
            $table->dropColumn('rto_deposit_received');
            $table->dropColumn('mf_amount_due');
            $table->dropColumn('rto_amount_due');
            $table->dropColumn('total_amount_due');
            $table->dropColumn('cash_deposit_amount');
            DB::statement('ALTER TABLE `orders` CHANGE `amount_received` `deposit_received` DOUBLE NULL DEFAULT NULL;');
        });
    }
}
