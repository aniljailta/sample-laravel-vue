<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('deliveries');

        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->date('promised_by_date')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('building_id')->unsigned()->nullable();
            $table->integer('start_location_id')->unsigned()->nullable();
            $table->integer('end_location_id')->unsigned()->nullable();
            $table->date('start_time')->nullable();
            $table->date('end_time')->nullable();
            $table->double('cost')->nullable();
            $table->integer('driver_id')->unsigned()->nullable();
            $table->integer('truck_id')->unsigned()->nullable();
            $table->integer('trailer_id')->unsigned()->nullable();
            $table->enum('status_id', ['draft', 'pending', 'estimated', 'confirmed', 'in_process', 'complete']);
            $table->enum('category_id', ['customer_delivery', 'inventory_move', 'customer_pickup', 'repo']);
            $table->enum('priority_id', ['normal', 'urgent', 'critical']);
            $table->double('distance')->nullable();
            $table->double('confirmed_distance')->nullable();
            $table->integer('setup_duration')->nullable();
            $table->double('drive_duration')->nullable();
            $table->double('average_drive_speed')->nullable();
            $table->string('dispatcher_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('sale_id')
                ->references('id')
                ->on('sales')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('start_location_id')
                ->references('id')
                ->on('locations')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('end_location_id')
                ->references('id')
                ->on('locations')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('truck_id')
                ->references('id')
                ->on('trucks')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('trailer_id')
                ->references('id')
                ->on('trailers')
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
        Schema::dropIfExists('deliveries');
    }
}
