<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetConstraintTypeForAlreadyExistingFieldsInOrderTable extends Migration
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
            DB::statement("UPDATE `options` SET `constraint_type` = 'equal_to' WHERE `options`.`force_quantity` IS NOT NULL");
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
            DB::statement("UPDATE `options` SET `constraint_type` = NULL WHERE `options`.`constraint_type` IS NOT NULL");
        });
    }
}
