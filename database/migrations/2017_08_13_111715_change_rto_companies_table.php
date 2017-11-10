<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRtoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rto_companies', function (Blueprint $table) {
            $table->renameColumn('id', COMPANY_ID);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->dropColumn('physical_address');
            $table->dropColumn('mailing_address');
            $table->dropColumn('primary_phone');
            $table->dropColumn('primary_contact');
            $table->dropColumn('logo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rto_companies', function (Blueprint $table) {
            $table->string('physical_address')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('logo')->nullable();

            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('zip');
            $table->renameColumn(COMPANY_ID, 'id');
        });
    }
}
