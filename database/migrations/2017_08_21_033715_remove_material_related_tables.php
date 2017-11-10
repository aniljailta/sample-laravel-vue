<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMaterialRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('material_allowable_colors', function (Blueprint $table) {
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('material_allowable_colors_color_id_foreign', $foreignKeys)){
                $table->dropForeign('material_allowable_colors_color_id_foreign');
            }
            if(in_array('material_allowable_colors_material_id_foreign', $foreignKeys)){
                $table->dropForeign('material_allowable_colors_material_id_foreign');
            }
        });

        Schema::table('material_allowable_models', function (Blueprint $table) {
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('material_allowable_models_building_model_id_foreign', $foreignKeys)){
                $table->dropForeign('material_allowable_models_building_model_id_foreign');
            }
            if(in_array('material_allowable_models_material_id_foreign', $foreignKeys)){
                $table->dropForeign('material_allowable_models_material_id_foreign');
            }
        });

        Schema::table('material_material_category', function (Blueprint $table) {
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('material_material_category_material_category_id_foreign', $foreignKeys)){
                $table->dropForeign('material_material_category_material_category_id_foreign');
            }
            if(in_array('material_material_category_material_id_foreign', $foreignKeys)){
                $table->dropForeign('material_material_category_material_id_foreign');
            }
        });

        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_allowable_colors');
        Schema::dropIfExists('material_allowable_models');
        Schema::dropIfExists('material_categories');
        Schema::dropIfExists('material_material_category');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->enum('category', ['roof', 'siding', 'trim']);
            $table->boolean('required')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_allowable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_model_id')->unsigned()->index();
            $table->integer('material_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_model_id')
                ->references('id')
                ->on('building_models')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('material_allowable_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('material_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_material_category', function (Blueprint $table) {
            $table->integer('material_id')->unsigned()->index();
            $table->integer('material_category_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('material_category_id')
                ->references('id')
                ->on('material_categories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
