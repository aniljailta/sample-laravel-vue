<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortIdToOptionCategoriesUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('option_categories', 'sort_id')) {
            Schema::table('option_categories', function (Blueprint $table) {
                $table->integer('sort_id')->nullable()->unsigned()->index()->after('qty_limit');
            }); 
        }

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
