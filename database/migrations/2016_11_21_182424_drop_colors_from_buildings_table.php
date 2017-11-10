<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColorsFromBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('body_color');
            $table->dropColumn('trim_color');
            $table->dropColumn('roof_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {

            $table->string('body_color')->after('build_date');
            $table->string('trim_color')->after('body_color');
            $table->string('roof_color')->after('trim_color');
        });
    }
}
