<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveFlagToBuildingPackageCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_package_categories', function (Blueprint $table) {
            $table->enum('is_active', ['yes', 'no', 'update_required'])->default('yes')->index()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_package_categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
