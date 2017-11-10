<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\OrderContact;
use App\Models\Customer;
use App\Models\User;

class ChangeCustomerForeignKeyInOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_contacts', function (Blueprint $table) {
            $table->dropForeign('order_contacts_customer_id_foreign');
        });

        $this->changeUsersToCustomers();

        Schema::table('order_contacts', function (Blueprint $table) {
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
        Schema::table('order_contacts', function (Blueprint $table) {
            $table->dropForeign('order_contacts_customer_id_foreign');
        });

        $this->changeCustomersToUsers();

        Schema::table('order_contacts', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    private function changeUsersToCustomers() {
        $orderContacts = OrderContact::all();

        foreach ($orderContacts as $orderContact) {
            if (!$orderContact->customer_id) continue;
            if (!$orderContact->order->order_reference->email) continue;
            $email = $orderContact->order->order_reference->email;
            $customer = Customer::where('email', $email)
                ->where(MANUFACTURER_COMPANY_ID, $orderContact->order->{MANUFACTURER_COMPANY_ID})
                ->first();

            if (!$customer) {
                $orderContact->customer_id = null;
                $orderContact->timestamps = false;
                $orderContact->save();
                continue;
            }

            $orderContact->customer_id = $customer->id;
            $orderContact->timestamps = false;
            $orderContact->save();
        }
    }

    private function changeCustomersToUsers() {
        $orderContacts = OrderContact::all();

        foreach ($orderContacts as $orderContact) {
            if (!$orderContact->customer_id) continue;

            $customer = Customer::find($orderContact->customer_id);
            if (!$customer) {
                $orderContact->customer_id = null;
                $orderContact->timestamps = false;
                $orderContact->save();
                continue;
            }

            $orderContact->customer_id = $customer->user_id;
            $orderContact->timestamps = false;
            $orderContact->save();
        }
    }
}
