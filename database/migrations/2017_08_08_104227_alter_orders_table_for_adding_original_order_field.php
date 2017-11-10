<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTableForAddingOriginalOrderField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('original_order')->after('status_id')->unsigned()->nullable();
            $table->float('change_order_fee', 8, 2)->after('original_order')->nullable();
            $table->foreign('original_order')->references('id')->on('orders');
        });

        $setting = new \App\Models\Setting();
        $setting->create([
            'id'          => 'change_order_fee',
            'title'       => 'Change Order Fee',
            'description' => 'Change Order Fee',
            'value'       => '1.00'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_original_order_foreign');
            $table->dropColumn('original_order');
            $table->dropColumn('change_order_fee');
        });

        $setting = new \App\Models\Setting();
        $setting->where('id', 'change_order_fee')->delete();
    }
}
