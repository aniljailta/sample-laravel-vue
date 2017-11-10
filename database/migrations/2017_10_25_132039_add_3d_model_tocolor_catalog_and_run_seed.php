<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add3dModelTocolorCatalogAndRunSeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('3d_model');
        });

        Schema::table('color_catalog', function (Blueprint $table) {
            $table->text('3d_model')->nullable()->after('sort_id');
        });

        \Artisan::call('db:seed', [
            '--class' => ColorCatalogSeeder::class,
            '--force' => true 
         ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->text('3d_model')->nullable()->after('sort_id');
        });

        Schema::table('color_catalog', function (Blueprint $table) {
            $table->dropColumn('3d_model');
        });
    }
}
