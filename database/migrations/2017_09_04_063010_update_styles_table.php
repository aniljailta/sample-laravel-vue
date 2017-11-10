<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('styles', function (Blueprint $table) {
            $table->integer('style_catalog_id')->nullable()->unsigned()->index()->after('icon_path');
            $table->integer('company_id')->nullable()->unsigned()->index()->after('style_catalog_id');
            $table->integer('sort_id')->nullable()->unsigned()->index()->after('company_id');

            $table->foreign('style_catalog_id')
                ->references('id')
                ->on('style_catalog')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
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
        Schema::table('styles', function (Blueprint $table) {
            $table->dropForeign(['style_catalog_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('style_catalog_id');
            $table->dropColumn('company_id');
            $table->dropColumn('sort_id');
        });
    }
}
