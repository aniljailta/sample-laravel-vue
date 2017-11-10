<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecurityDepositToInventoryBuilding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_buildings', function (Blueprint $table) {
            $table->double('inventory_building_security_deposit')->default(0)->after('inventory_building_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_buildings', function (Blueprint $table) {
            $table->dropColumn('inventory_building_security_deposit');
        });
    }
}
