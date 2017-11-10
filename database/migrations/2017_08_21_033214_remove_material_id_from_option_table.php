<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMaterialIdFromOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            //
            $conn = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = array_map(function($key) {
                return $key->getName();
            }, $conn->listTableForeignKeys($table->getTable()));
            if(in_array('options_material_id_foreign', $foreignKeys)){
                $table->dropForeign('options_material_id_foreign');
            }
            $table->dropColumn('material_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            //
            $table->integer('material_id')->unsigned()->nullable()->index()->after('is_active');
            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
