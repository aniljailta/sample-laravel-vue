<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;

class ChangeCustomerForeignKeyInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_customer_id_foreign');
        });

        $this->changeUsersToCustomers();

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('restrict');
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
            $table->dropForeign('orders_customer_id_foreign');
        });

        $this->changeCustomersToUsers();

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    private function changeUsersToCustomers() {
        $orders = Order::all();

        foreach ($orders as $order) {
            if (!$order->customer_id) continue;
            if (!$order->order_reference->email) continue;

            $email = $order->order_reference->email;
            $customer = Customer::where('email', $email)
                ->where(MANUFACTURER_COMPANY_ID, $order->{MANUFACTURER_COMPANY_ID})
                ->first();

            if (!$customer) {
                $order->customer_id = null;
                $order->timestamps = false;
                $order->save();
                continue;
            }

            $order->customer_id = $customer->id;
            $order->timestamps = false;
            $order->save();
        }
    }

    private function changeCustomersToUsers() {
        $orders = Order::all();

        foreach ($orders as $order) {
            if (!$order->customer_id) continue;

            $customer = Customer::find($order->customer_id);
            if (!$customer) {
                $order->customer_id = null;
                $order->timestamps = false;
                $order->save();
                continue;
            }

            $order->customer_id = $customer->user_id;
            $order->timestamps = false;
            $order->save();
        }
    }
}
