<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultStatusFlagForSaleToBuildingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_statuses', function (Blueprint $table) {
            $table->tinyInteger('default_for_sale')->nullable()->after('priority');
        });

        DB::table('building_statuses')->where('name', 'Pending')->update(['default_for_sale' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_statuses', function (Blueprint $table) {
            $table->dropColumn('default_for_sale');
        });
    }
}
