<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultsInColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->integer('use_body')->default(0)->change();
            $table->integer('use_trim')->default(0)->change();
            $table->integer('use_shingle')->default(0)->change();
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
            $table->integer('use_body')->default(false)->change();
            $table->integer('use_trim')->default(false)->change();
            $table->integer('use_shingle')->default(false)->change();
        });
    }
}
