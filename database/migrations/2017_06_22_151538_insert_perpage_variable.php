<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPerpageVariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('settings')->where('id', 'per_page')->first();
        if (!$exists) {
            DB::table('settings')->insert([
                'id' => 'per_page',
                'title' => 'Per Page count',
                'description' => 'Default Per Page count for pages',
                'value' => 30,
                'created_at' =>  \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('id', 'per_page')->delete();
    }
}
