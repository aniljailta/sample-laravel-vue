<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortIdAndColorCatalogIdToColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('colors', function (Blueprint $table) {
            $table->integer('sort_id')->nullable()->unsigned()->index()->after('id');
            $table->integer('color_catalog_id')->nullable()->unsigned()->index()->after('is_active');

            $table->foreign('color_catalog_id')
                ->references('id')
                ->on('color_catalog')
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
        Schema::table('colors', function (Blueprint $table) {
            $table->dropForeign(['color_catalog_id']);
            $table->dropColumn('color_catalog_id');
            $table->dropColumn('sort_id');
        });
    }
}
