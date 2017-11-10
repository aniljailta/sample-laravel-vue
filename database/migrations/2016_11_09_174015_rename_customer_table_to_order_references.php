<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCustomerTableToOrderReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('customers', 'order_references');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_customer_id_foreign');
            
            $table->renameColumn('customer_id', 'reference_id');
            
            $table->foreign('reference_id')
                ->references('id')
                ->on('order_references')
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
        Schema::rename('order_references', 'customers');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_reference_id_foreign');

            $table->renameColumn('reference_id', 'customer_id');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
}
