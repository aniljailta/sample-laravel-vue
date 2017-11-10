<?php

use App\Models\ManufacturerCompany;
use App\Models\Option;
use App\Models\OptionCatalog;
use App\Models\Order;
use App\Models\OrderOption;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderFlagsToOptionsAndCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->boolean('rto_deposit')->nullable()->after('is_active');
            $table->boolean('delivery_charge')->nullable()->after('is_active');
        });

        Schema::table('option_catalog', function (Blueprint $table) {
            $table->boolean('rto_deposit')->nullable()->after('is_active');
            $table->boolean('delivery_charge')->nullable()->after('is_active');
        });

        Option::query()->where('name', 'Extra Delivery Charge')->update([
            'delivery_charge' => true,
            'rto_deposit' => true
            ]);
        OptionCatalog::query()->where('name', 'Extra Delivery Charge')->update([
            'delivery_charge' => true,
            'rto_deposit' => true
            ]);

        $manufacturerCompanies = ManufacturerCompany::all();
        foreach ($manufacturerCompanies as $manufacturerCompany) {
            $option = $this->getDeliveryChargeOption($manufacturerCompany);
            if ($option) {
                $this->addDeliveryChargeOption($manufacturerCompany, $option);
            }
        }
    }

    /**
     * @param ManufacturerCompany $manufacturerCompany
     * @return Option
     */
    private function getDeliveryChargeOption(ManufacturerCompany $manufacturerCompany): Option {
        $deliveryChargeOption = Option::where(MANUFACTURER_COMPANY_ID, $manufacturerCompany->company_id)
        ->where('name', 'Extra Delivery Charge')
        ->first();
        if (!$deliveryChargeOption) {
            $catalogOption = Option::where('name', 'Extra Delivery Charge')->first();
            if ($catalogOption) {
                $deliveryChargeOption = new Option;
                $deliveryChargeOption->{MANUFACTURER_COMPANY_ID} = $manufacturerCompany->company_id;
                $deliveryChargeOption->category_id = $catalogOption->category_id;
                $deliveryChargeOption->force_quantity = $catalogOption->force_quantity;
                $deliveryChargeOption->name = $catalogOption->name;
                $deliveryChargeOption->description = $catalogOption->description;
                $deliveryChargeOption->unit_price = $catalogOption->unit_price;
                $deliveryChargeOption->is_active = $catalogOption->is_active;
                $deliveryChargeOption->delivery_charge = $catalogOption->delivery_charge;
                $deliveryChargeOption->rto_deposit = $catalogOption->rto_deposit;
                $deliveryChargeOption->taxable = $catalogOption->taxable;
                $deliveryChargeOption->constraint_type = $catalogOption->constraint_type;
                $deliveryChargeOption->default_color_id = $catalogOption->default_color_id;
                $deliveryChargeOption->sort_id = $catalogOption->sort_id;
                $deliveryChargeOption->option_catalog_id = null;
                $deliveryChargeOption->save();

            }
        }

        return $deliveryChargeOption;
    }

    /**
     * @param ManufacturerCompany $manufacturerCompany
     * @param Option $option
     */
    private function addDeliveryChargeOption(ManufacturerCompany $manufacturerCompany, Option $option) {
        $orders = Order::where(MANUFACTURER_COMPANY_ID, $manufacturerCompany->company_id)
        ->where('delivery_charge', '>', 0)
        ->whereDoesntHave('options', function($orderOption) {
            $orderOption->whereHas('option', function ($option) {
                $option->where('name', 'Extra Delivery Charge');
            });
        })
        ->get();

        foreach ($orders as $order) {
            $order->options()->save(new OrderOption([
                'option_id' => $option->id,
                'unit_price' => $order->delivery_charge,
                'quantity' => 1,
                'total_price' => $order->delivery_charge
                ]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('rto_deposit');
            $table->dropColumn('delivery_charge');
        });

        Schema::table('option_catalog', function (Blueprint $table) {
            $table->dropColumn('rto_deposit');
            $table->dropColumn('delivery_charge');
        });
    }
}
