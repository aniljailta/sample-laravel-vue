<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEsignFromOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_esigned');
            $table->dropColumn('esigned_on');
            $table->dropColumn('esign_signature_id');
            $table->dropColumn('esign_user_agent');
            $table->dropColumn('esign_ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('is_esigned', ['yes', 'no'])->default('no')->index();
            $table->timestamp('esigned_on')->nullable();
            $table->string('esign_signature_id')->nullable();
            $table->text('esign_user_agent')->nullable();
            $table->string('esign_ip_address')->nullable();
        });
    }
}
