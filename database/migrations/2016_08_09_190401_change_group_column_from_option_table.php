<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGroupColumnFromOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {

            if(Schema::hasColumn('options', 'group'))
                $table->dropColumn('group');

            $table->integer('category_id')->unsigned()->nullable()->default(1)->index()->after('id');
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
        Schema::table('options', function (Blueprint $table) {
            $table->dropForeign('options_category_id_foreign');
            $table->dropColumn('category_id');
            $table->string('group')->nullable()->after('id');
        });
    }
}
