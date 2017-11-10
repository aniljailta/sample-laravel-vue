<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCompanyIdAndAddSoftDeletesToStyles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('styles', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->integer('company_id')->nullable()->unsigned()->index()->after('style_catalog_id');
        $table->foreign('company_id')
              ->references('id')
              ->on('companies')
              ->onUpdate('cascade')
              ->onDelete('restrict');
    }
}
