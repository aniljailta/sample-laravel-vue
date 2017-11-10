<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagsToOptionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            $table->tinyInteger('is_required')->unsigned()->nullable()->index()->after('group');
            $table->tinyInteger('qty_limit')->unsigned()->nullable()->index()->after('is_required');
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
            $table->dropColumn('is_required');
            $table->dropColumn('qty_limit');
        });
    }
}
