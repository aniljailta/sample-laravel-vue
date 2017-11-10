<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortIdAndOptionCatalogIdToOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('sort_id')->nullable()->unsigned()->index()->after('default_color_id');
            $table->integer('option_catalog_id')->nullable()->unsigned()->index()->after('sort_id');

            $table->foreign('option_catalog_id')
                ->references('id')
                ->on('option_catalog')
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
        Schema::table('options', function (Blueprint $table) {
            $table->dropForeign(['option_catalog_id']);
            $table->dropColumn('option_catalog_id');
            $table->dropColumn('sort_id');
        });
    }
}
