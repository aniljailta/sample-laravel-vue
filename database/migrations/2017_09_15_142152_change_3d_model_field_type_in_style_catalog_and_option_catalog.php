<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Change3dModelFieldTypeInStyleCatalogAndOptionCatalog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('style_catalog', function (Blueprint $table) {
            $table->dropColumn('3d_model');
        });
        Schema::table('style_catalog', function (Blueprint $table) {
            $table->text('3d_model')->nullable()->after('sort_id');
        });

        Schema::table('option_catalog', function (Blueprint $table) {
            $table->dropColumn('3d_model');
        });
        Schema::table('option_catalog', function (Blueprint $table) {
            $table->text('3d_model')->nullable()->after('sort_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('style_catalog', function (Blueprint $table) {
            $table->dropColumn('3d_model');
            $table->json('3d_model')->nullable()->after('sort_id');
        });

        Schema::table('option_catalog', function (Blueprint $table) {
            $table->dropColumn('3d_model');
            $table->json('3d_model')->nullable()->after('sort_id');
        });
    }
}
