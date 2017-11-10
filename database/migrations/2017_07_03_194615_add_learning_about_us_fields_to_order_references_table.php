<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLearningAboutUsFieldsToOrderReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_references', function (Blueprint $table) {
            $table->string('learning_about_us')->nullable()->after('building_location_zip');
            $table->string('learning_about_us_other')->nullable()->after('learning_about_us');
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
            $table->dropColumn('learning_about_us');
            $table->dropColumn('learning_about_us_other');
        });
    }
}
