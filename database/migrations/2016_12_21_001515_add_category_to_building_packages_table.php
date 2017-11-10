<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToBuildingPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_packages', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->nullable()->index()->after('building_model_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('building_package_categories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_packages', function (Blueprint $table) {
            $table->dropForeign('building_packages_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
}
