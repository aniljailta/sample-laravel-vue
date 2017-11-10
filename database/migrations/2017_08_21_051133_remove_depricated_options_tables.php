<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDepricatedOptionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('option_package_allowable_models', function (Blueprint $table) {
            //
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('option_package_allowable_models_building_model_id_foreign', $foreignKeys)){
                $table->dropForeign('option_package_allowable_models_building_model_id_foreign');
            }
            if(in_array('option_package_allowable_models_building_model_id_foreign', $foreignKeys)){
                $table->dropForeign('option_package_allowable_models_option_package_id_foreign');
            }
        });
        Schema::table('option_package_options', function (Blueprint $table) {
            //
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('option_package_options_option_id_foreign', $foreignKeys)){
                $table->dropForeign('option_package_options_option_id_foreign');
            }
            if(in_array('option_package_options_option_package_id_foreign', $foreignKeys)){
                $table->dropForeign('option_package_options_option_package_id_foreign');
            }
        });
        Schema::dropIfExists('option_packages');
        Schema::dropIfExists('option_package_allowable_models');
        Schema::dropIfExists('option_package_options');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('option_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('total_price')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('option_package_allowable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_package_id')->unsigned()->index();
            $table->integer('building_model_id')->unsigned()->index();

            $table->timestamps();

            $table->foreign('option_package_id')
                ->references('id')
                ->on('option_packages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_model_id')
                ->references('id')
                ->on('building_models')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('option_package_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_package_id')->unsigned()->index();
            $table->integer('option_id')->unsigned()->index();
            $table->double('unit_price');

            $table->timestamps();

            $table->foreign('option_package_id')
                ->references('id')
                ->on('option_packages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('option_id')
                ->references('id')
                ->on('options')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
