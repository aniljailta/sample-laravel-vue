<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable()->default(1)->index();
            $table->enum('force_quantity', ['building_length','wall_area','floor_area'])->nullable()->index();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->double('unit_price');
            $table->enum('is_active', ['yes', 'no'])->index();
            $table->integer('sort_id')->unsigned()->index()->nullable();
            $table->json('3d_model')->nullable();
            $table->boolean('rto')->nullable();
            $table->boolean('taxable')->nullable();
            $table->enum('constraint_type', ['less_than', 'equal_to'])->nullable();
            $table->integer('default_color_id')->unsigned()->nullable();
            $table->boolean('is_required')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')
                ->references('id')
                ->on('option_categories')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('option_catalog');
    }
}
