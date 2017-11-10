<?php

use Illuminate\Database\Seeder;

class OrderUpdateSeeder0304 extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        $this->updateOrders();
        
        Log::info(__CLASS__ . ' End');
    }

    /**
     * Recalculate all orders
     */
    private function updateOrders() {
        DB::statement("UPDATE `orders` o 
                       JOIN `dealers` d 
                       ON o.dealer_id = d.id 
                       SET o.dealer_commission = o.total_sales_price * (d.commission_rate/100), 
                           o.dealer_commission_rate = d.commission_rate, 
                           o.dealer_tax_rate = d.tax_rate");
    }
}
