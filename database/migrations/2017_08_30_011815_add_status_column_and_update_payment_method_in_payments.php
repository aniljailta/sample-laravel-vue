<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnAndUpdatePaymentMethodInPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE payments CHANGE COLUMN payment_method payment_method ENUM('cash', 'check', 'credit_card', 'ach', 'wire_transfer')");
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'complete', 'cancelled'])->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
