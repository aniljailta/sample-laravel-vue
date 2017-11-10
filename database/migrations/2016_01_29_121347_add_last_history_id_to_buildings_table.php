<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastHistoryIdToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {

            $table->integer('last_history_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('last_history_id')
                ->references('id')
                ->on('building_history')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {

            $table->dropForeign('buildings_last_history_id_foreign');
            $table->dropColumn('last_history_id');

        });
    }
}
