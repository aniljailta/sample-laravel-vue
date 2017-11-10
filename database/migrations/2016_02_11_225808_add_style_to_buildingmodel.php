<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStyleToBuildingmodel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_models', function (Blueprint $table) {
            $table->dropColumn('short_code');

            $table->integer('style_id')->unsigned()->index()->after('id');
            $table->foreign('style_id')
                  ->references('id')
                  ->on('styles')
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
        Schema::table('building_models', function (Blueprint $table) {
            $table->string('short_code', 65);

            $table->dropForeign('building_models_style_id_foreign');
            $table->dropColumn('style_id');
        });
    }
}
