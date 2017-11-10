<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('storable_id')->unsigned()->index();
            $table->string('storable_type')->index();

            $table->string('type', 128)->index()->nullable();
            $table->string('mime', 128)->index()->nullable();
            
            $table->string('path', 255);
            $table->string('name', 255)->index();
            $table->string('ext', 128)->index();
            $table->text('description')->nullable();
            $table->integer('source_id')->unsigned()->index()->nullable();
            $table->string('reason', 255)->nullable();
            $table->integer('size')->unsigned()->index();
            $table->integer('width')->unsigned()->index()->nullable();
            $table->integer('height')->unsigned()->index()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('files');
    }
}
