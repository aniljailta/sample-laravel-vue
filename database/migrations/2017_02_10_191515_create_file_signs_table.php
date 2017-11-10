<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_signs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned()->index();
            $table->enum('is_esigned', ['yes', 'no'])->default('no')->index();
            $table->timestamp('esigned_on')->nullable()->index();
            $table->string('esign_signature_request_id')->nullable()->index();
            $table->string('esign_signature_id')->nullable()->index();
            $table->text('esign_user_agent')->nullable();
            $table->string('esign_ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
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
        Schema::drop('file_signs');
    }
}
