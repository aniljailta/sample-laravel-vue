<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturerRtoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_rto_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer(MANUFACTURER_COMPANY_ID)->unsigned();
            $table->integer(RTO_COMPANY_ID)->unsigned();
            $table->timestamps();

            $table->foreign(MANUFACTURER_COMPANY_ID)
                ->references(COMPANY_ID)
                ->on('manufacturer_companies')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign(RTO_COMPANY_ID)
                ->references(COMPANY_ID)
                ->on('rto_companies')
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
        Schema::dropIfExists('manufacturer_rto_companies');
    }
}
