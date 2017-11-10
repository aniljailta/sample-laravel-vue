<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIconPathFieldFromStylesAndStyleCatalog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('styles', function (Blueprint $table) {
            $table->dropColumn('icon_path');
        });

        Schema::table('style_catalog', function (Blueprint $table) {
            $table->dropColumn('icon_path');
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
            $table->text('icon_path')->nullable();
        });

        Schema::table('style_catalog', function (Blueprint $table) {
            $table->text('icon_path')->nullable();
        });
    }
}
