<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCancelledStatusInSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE sales MODIFY COLUMN status_id ENUM('open','invoiced','closed','cancelled')");

        // Set all empty or null statuses to cancelled
        $update = DB::table('sales')
            ->whereNull('status_id')
            ->orWhere('status_id', '')
            ->update(['status_id' => 'cancelled']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Set all cancelled statuses to null
        $update = DB::table('sales')
            ->where(['status_id' => 'cancelled'])
            ->update(['status_id' => NULL]);
        
        DB::statement("ALTER TABLE sales MODIFY COLUMN status_id ENUM('open','invoiced','closed')");
    }
}
