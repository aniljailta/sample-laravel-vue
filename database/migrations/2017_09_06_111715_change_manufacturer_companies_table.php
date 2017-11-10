<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeManufacturerCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufacturer_companies', function (Blueprint $table) {
            $table->string('mail_from')->nullable()->after('mail_username');
            $table->string('hellosign_client_id', 32)->nullable()->after('mail_from');

            $table->dropColumn('boss_first_name');
            $table->dropColumn('boss_last_name');
            $table->dropColumn('boss_email');
            $table->dropColumn('boss_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturer_companies', function (Blueprint $table) {
            $table->dropColumn('mail_from');
            $table->dropColumn('hellosign_client_id');

            $table->string('boss_first_name')->nullable();
            $table->string('boss_last_name')->nullable();
            $table->string('boss_email')->nullable();
            $table->string('boss_phone')->nullable();
        });
    }
}
