<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupToOptionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            $table->string('group')->nullable()->index()->after('name');
            $table->index('name');
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
            $table->dropIndex(['name']);
            $table->dropIndex(['group']);
            $table->dropColumn('group');
        });
    }
}
