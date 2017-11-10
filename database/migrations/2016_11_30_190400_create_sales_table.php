<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status_id', ['open', 'invoiced', 'closed'])->nullable()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->integer('building_id')->unsigned()->index();
            $table->integer('location_id')->unsigned()->index();
            $table->string('invoice_id');
            $table->date('invoice_date');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
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
        Schema::drop('sales');
    }
}
