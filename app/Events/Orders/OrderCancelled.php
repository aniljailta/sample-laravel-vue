<?php
namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderCancelled
{
    use SerializesModels;

    /**
     * @var Order
     */
    private $order;

    /**
     * OrderCancelled constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}