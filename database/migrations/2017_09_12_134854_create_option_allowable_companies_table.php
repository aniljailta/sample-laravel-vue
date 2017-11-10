<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionAllowableCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_allowable_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_catalog_id')->unsigned()->index();
            $table->integer(MANUFACTURER_COMPANY_ID)->unsigned();

            $table->timestamps();

            $table->foreign('option_catalog_id')
                ->references('id')
                ->on('option_catalog')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign(MANUFACTURER_COMPANY_ID)
                ->references(COMPANY_ID)
                ->on('manufacturer_companies')
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
        Schema::drop('option_allowable_companies');
    }
}
