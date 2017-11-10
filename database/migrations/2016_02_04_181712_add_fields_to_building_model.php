<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBuildingModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_models', function (Blueprint $table) {
            $table->integer('width')->unsigned()->after('description');
            $table->integer('wall_height')->unsigned()->after('width');
            $table->integer('length')->unsigned()->after('wall_height');
            $table->double('shell_price')->default(0)->after('length');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_models', function (Blueprint $table) {
            $table->dropColumn('width');
            $table->dropColumn('wall_height');
            $table->dropColumn('length');
            $table->dropColumn('shell_price');
        });
    }
}
