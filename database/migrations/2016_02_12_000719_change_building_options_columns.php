<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBuildingOptionsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_options', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->double('total_price')->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_options', function (Blueprint $table) {
            $table->dropColumn('total_price');
            $table->double('price')->default(0)->after('quantity');
        });
    }
}
