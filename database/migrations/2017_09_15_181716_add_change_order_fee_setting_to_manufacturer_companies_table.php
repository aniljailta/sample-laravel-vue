<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeOrderFeeSettingToManufacturerCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufacturer_companies', function (Blueprint $table) {
            $table->double('change_order_fee')->nullable()->after('initial_contact_eligibility');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturer_companies', function (Blueprint $table) {
            $table->double('change_order_fee');
        });
    }
}
