<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort_id')->nullable()->unsigned()->index();
            $table->string('type')->nullable()->index();
            $table->string('name');
            $table->string('hex')->nullable();
            $table->string('url')->nullable();
            $table->enum('is_active', ['yes', 'no', 'update_required'])->default('yes')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_catalog');
    }
}
