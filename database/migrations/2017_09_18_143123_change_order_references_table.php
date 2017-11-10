<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrderReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_references', function (Blueprint $table) {
           $table->dropColumn([
               'renter_dob',
               'renter_ssn',
               'renter_dln',
               'co_renter_dob',
               'co_renter_ssn',
               'co_renter_dln',
           ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_references', function (Blueprint $table) {
            $table->string('renter_dob')->nullable();
            $table->string('renter_ssn')->nullable();
            $table->string('renter_dln')->nullable();
            $table->string('co_renter_dob')->nullable();
            $table->string('co_renter_ssn')->nullable();
            $table->string('co_renter_dln')->nullable();
        });
    }
}
