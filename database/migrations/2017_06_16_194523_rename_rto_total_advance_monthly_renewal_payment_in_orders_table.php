<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRtoTotalAdvanceMonthlyRenewalPaymentInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `orders` CHANGE `rto_total_advanceMonthly_renewal_payment` `rto_total_advance_monthly_renewal_payment` DOUBLE NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `orders` CHANGE `rto_total_advance_monthly_renewal_payment` `rto_total_advanceMonthly_renewal_payment` DOUBLE NULL DEFAULT NULL');
    }
}
