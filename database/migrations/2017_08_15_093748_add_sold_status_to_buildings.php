<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoldStatusToBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('sold_status')->nullable()->default(null);
        });

        DB::statement("UPDATE buildings LEFT JOIN sales ON sales.building_id = buildings.id SET buildings.sold_status = 'sold' WHERE sales.status_id = 'invoiced' OR sales.status_id = 'open'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('sold_status');
        });
    }
}
