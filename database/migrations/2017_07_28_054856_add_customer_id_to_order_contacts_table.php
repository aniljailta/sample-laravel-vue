<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\OrderContact;

class AddCustomerIdToOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('order_contacts', 'customer_id')) {
            return true;
        }

        Schema::table('order_contacts', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned()->nullable()->after('order_id');
            $table->foreign('customer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });

        \DB::transaction(function() {
            $contacts = OrderContact::with('order')->get();
            $contacts->each(function($contact) {
                if ($contact->order->customer_id !== null) {
                    $contact->customer_id = $contact->order->customer_id;
                    $contact->save();
                }
            });
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
            $table->dropColumn('customer_id');
        });
    }
}
