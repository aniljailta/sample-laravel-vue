<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('type')->nullable(); // order | quote
            $table->integer('dealer_id')->nullable()->unsigned()->index();
            $table->integer('customer_id')->nullable()->unsigned()->index();
            $table->string('sales_person')->nullable(); // name
            $table->string('sale_type')->nullable(); // dealer-inventory | custom-order
            $table->string('payment_type')->nullable();
            $table->string('building_condition')->nullable(); // new | used
            $table->integer('building_id')->nullable()->unsigned()->index();
            $table->string('building_serial')->nullable();
            $table->integer('building_style_id')->nullable()->unsigned()->index();
            $table->integer('building_model_id')->nullable()->unsigned()->index();
            $table->string('body_color')->nullable();
            $table->string('trim_color')->nullable();
            $table->string('roof_color')->nullable();
            $table->string('rto_type')->nullable(); // buydown | no-buydown
            $table->string('rto_term')->nullable(); // 24/36/48/60 days
            $table->double('gross_buydown')->nullable();
            $table->double('deposit_received')->nullable();
            $table->string('payment_method')->nullable(); // cash | check | credit_card
            $table->string('transaction_id')->nullable();

            $table->double('delivery_charge')->nullable();
            $table->tinyInteger('dr_level_pad')->unsigned()->index()->nullable();
            $table->tinyInteger('dr_soft_when_wet')->unsigned()->index()->nullable();
            $table->tinyInteger('dr_width_restrictions')->unsigned()->index()->nullable();
            $table->tinyInteger('dr_height_restrictions')->unsigned()->index()->nullable();
            $table->tinyInteger('dr_reqires_site_visit')->unsigned()->index()->nullable();
            $table->tinyInteger('dr_must_cross_neighboring_prop')->unsigned()->index()->nullable();
            $table->text('dr_notes')->nullable();
            $table->date('customer_expects_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('dealer_id')
                ->references('id')
                ->on('dealers')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_style_id')
                ->references('id')
                ->on('styles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('building_model_id')
                ->references('id')
                ->on('building_models')
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
        Schema::drop('orders');
    }
}
