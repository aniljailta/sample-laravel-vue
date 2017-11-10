<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculateCommission
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasUpdated  $event
     * @return void
     */
    public function handle(OrderWasUpdated $event)
    {
        // Update order
        if (!$event->order->dealer) return;
        
        $event->order->dealer_commission_rate = $event->order->dealer->commission_rate;
        $event->order->dealer_commission = $event->order->total_sales_price * ($event->order->dealer->commission_rate/100);
        $event->order->save();
    }
}
