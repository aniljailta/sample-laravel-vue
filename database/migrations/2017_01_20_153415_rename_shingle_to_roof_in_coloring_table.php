<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameShingleToRoofInColoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename value
        DB::table('coloring')
            ->where('type', 'shingle')
            ->update([
                "type" => 'roof',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename value
        DB::table('coloring')
            ->where('type', 'roof')
            ->update([
                "type" => 'shingle',
            ]);
    }
}
