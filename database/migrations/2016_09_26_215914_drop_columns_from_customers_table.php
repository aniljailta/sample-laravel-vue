<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('apartment_number');
            $table->dropColumn('cell_phone_number1');
            $table->dropColumn('email_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {

            $table->string('apartment_number')->nullable()->after('building_location_zip');
            $table->string('cell_phone_number1')->nullable()->after('landlord_phone_number');
            $table->string('email_address')->nullable()->after('home_phone_number');
        });
    }
}
