<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagsAndMaterialColumnsToOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {

            // $table->jsonb('attrs')->nullable()->after('unit_price');
            $table->enum('force_quantity', ['building_length'])->nullable()->index()->after('category_id');
            $table->integer('material_id')->unsigned()->nullable()->index()->after('is_active');
            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
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
        Schema::table('options', function (Blueprint $table) {
            // $table->dropColumn('attrs');
            $table->dropColumn('force_quantity');
            $table->dropForeign('options_material_id_foreign');
            $table->dropColumn('material_id');
        });
    }
}
