<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorAllowableCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_allowable_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('color_catalog_id')->unsigned()->index();
            $table->integer(MANUFACTURER_COMPANY_ID)->unsigned();

            $table->timestamps();

            $table->foreign('color_catalog_id')
                ->references('id')
                ->on('color_catalog')
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
        Schema::dropIfExists('color_allowable_companies');
    }
}
