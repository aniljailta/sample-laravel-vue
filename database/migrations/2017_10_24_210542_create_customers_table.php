<?php

use App\Models\Order;
use App\Services\Customers\CustomerService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger(MANUFACTURER_COMPANY_ID);
            $table->unique(['email', MANUFACTURER_COMPANY_ID], 'customers_email_unique');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign(MANUFACTURER_COMPANY_ID)
                ->references(COMPANY_ID)
                ->on('manufacturer_companies')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        DB::transaction(function() {
            $orders = Order::where('status_id', 'sale_generated')->get();
            $customerService = new CustomerService;

            foreach($orders as $order) {
                $customerService->createCustomerFromOrder($order);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customers');
    }
}
