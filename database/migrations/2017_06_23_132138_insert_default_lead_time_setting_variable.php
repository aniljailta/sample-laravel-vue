<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultLeadTimeSettingVariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('settings')->where('id', 'lead_time')->first();
        if (!$exists) {
            DB::table('settings')->insert([
                'id' => 'lead_time',
                'title' => 'Lead Time',
                'description' => 'Used for \'customer expects by date\' (period). or...',
                'value' => 21,
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
        DB::table('settings')->where('id', 'lead_time')->delete();
    }
}
