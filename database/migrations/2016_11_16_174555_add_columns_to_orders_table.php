<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status_id', [
                'draft', 
                'submitted',
                'review_needed',
                'sale_generated'
            ])
                ->default('draft')
                ->after('uuid')
                ->index();
            $table->text('note_admin')->nullable()->after('dealer_notes');
            $table->text('note_dealer')->nullable()->after('note_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_id');
            $table->dropColumn('note_admin');
            $table->dropColumn('note_dealer');
        });
    }
}
