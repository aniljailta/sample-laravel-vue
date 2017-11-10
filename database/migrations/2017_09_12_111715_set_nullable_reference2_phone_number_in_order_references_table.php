<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullableReference2PhoneNumberInOrderReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_references', function (Blueprint $table) {
            $table->string('reference2_phone_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_references', function (Blueprint $table) {
            $table->string('reference2_phone_number')->nullable(false)->change();
        });
    }
}
