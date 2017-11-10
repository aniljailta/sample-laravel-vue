<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerComissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_commission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer(MANUFACTURER_COMPANY_ID)->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('dealer_id')->unsigned();
            $table->enum('status', ['pending', 'processed', 'cancelled'])->nullable();
            $table->double('commission_rate')->nullable();
            $table->double('dealer_discount')->nullable();
            $table->decimal('amount_due')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign(MANUFACTURER_COMPANY_ID)
                ->references(COMPANY_ID)
                ->on('manufacturer_companies')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('dealer_id')
                ->references('id')
                ->on('dealers')
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
        Schema::dropIfExists('dealer_commission');
    }
}
