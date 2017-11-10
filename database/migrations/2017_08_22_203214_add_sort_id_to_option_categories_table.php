<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortIdToOptionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            // $table->integer('sort_id')->unsigned()->nullable()->after('qty_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            $table->dropColumn('sort_id');
        });
    }
}
