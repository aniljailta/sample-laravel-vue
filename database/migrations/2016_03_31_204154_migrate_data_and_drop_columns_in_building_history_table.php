<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateDataAndDropColumnsInBuildingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get records from old column.
        $results = DB::table('building_history')->whereNotNull('contractor_id')->get();

        foreach($results as $result)
        {
            DB::table('expenses')->insert([
                "expense_id"    =>  $result->id,
                "expense_type"    =>  'status',
                "cost"    =>  $result->cost,
                "created_at"    =>  $result->created_at,
                "updated_at"    =>  $result->updated_at,
            ]);
        }

        Schema::table('building_history', function (Blueprint $table) {
            // Delete old column.
            $table->dropColumn('cost'); // moved to expenses
            $table->dropColumn('invoice_number'); // unused
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_history', function (Blueprint $table) {
            $table->double('cost')->nullable();
            $table->string('invoice_number', 255)->nullable();
        });

        $results = DB::table('expenses')->where('expense_type', 'status')->get();

        foreach($results as $result)
        {
            DB::table('building_history')
                ->where('id', $result->expense_id)
                ->update([
                    "cost"    =>  $result->cost,
                ]);
        }
    }
}
