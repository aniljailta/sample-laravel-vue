<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Order;
use App\Services\Orders\OrderCalculator;

class ChangeCalculatedFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement('ALTER TABLE `orders` CHANGE `cash_deposit_amount` `po_deposit_amount` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `mf_amount_due` `mf_deposit_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `rto_amount_due` `rto_deposit_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `total_amount_due` `total_deposit_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `balance` `balance_due` DOUBLE NULL DEFAULT NULL;');
            $table->double('total_purchase', 7, 2)->nullable()->after('total_sales_price');
            $table->double('rto_deposit', 7, 2)->nullable()->after('security_deposit');
        });

        DB::transaction(function () {
            $orders = Order::with('dealer', 'building')
                ->has('dealer')
                ->has('building')
                ->get();

            $orders->each(function($order) {
                $tmpOrder = clone $order;
                $tmpOrder = (new OrderCalculator)
                    ->setOrder($tmpOrder)
                    ->setDealer($tmpOrder->dealer)
                    ->setBuilding($tmpOrder->building)
                    ->calculateOrder()
                    ->getOrder();
                $order->total_purchase = $tmpOrder->total_purchase;
                $order->rto_deposit = $tmpOrder->rto_deposit;
                $order->po_deposit_amount = $tmpOrder->po_deposit_amount;
                $order->mf_deposit_amount_due = $tmpOrder->mf_deposit_amount_due;
                $order->rto_deposit_amount_due = $tmpOrder->rto_deposit_amount_due;
                $order->total_deposit_amount_due = $tmpOrder->total_deposit_amount_due;
                $order->save();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('rto_deposit');
            $table->dropColumn('total_purchase');
            DB::statement('ALTER TABLE `orders` CHANGE `po_deposit_amount` `cash_deposit_amount` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `mf_deposit_amount_due` `mf_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `rto_deposit_amount_due` `rto_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `total_deposit_amount_due` `total_amount_due` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `orders` CHANGE `balance_due` `balance` DOUBLE NULL DEFAULT NULL;');
        });
    }
}
