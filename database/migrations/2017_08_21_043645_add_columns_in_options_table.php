<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInOptionsTable extends Migration
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
            $table->integer('default_color_id')->unsigned()->nullable()->after('is_active');
            $table->enum('constraint_type', ['less_than', 'equal_to'])->nullable()->after('is_active');
            $table->boolean('taxable')->nullable()->after('is_active');
            $table->boolean('rto')->nullable()->after('is_active');
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
            $table->dropColumn('default_color_id');
            $table->dropColumn('constraint_type');
            $table->dropColumn('taxable');
            $table->dropColumn('rto');
        });
    }
}
