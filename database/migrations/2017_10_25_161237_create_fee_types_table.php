<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeTypesTable extends Migration
{
    const TYPE = 'type';
    const SLUG = 'slug';
    const NAME = 'name';
    const DESCRIPTION = 'description';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('slug');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();

        Artisan::call('db:seed', ['--class' => FeeTypesSeeder::class, '--force' => true]);

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_types');
    }
}
