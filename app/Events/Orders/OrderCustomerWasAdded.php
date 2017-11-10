<?php

namespace App\Events\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCustomerWasAdded
{
    use SerializesModels;

    /**
     * @var Order
     * @var User
     */
    public $order;
    public $customer;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param User  $customer
     */
    public function __construct(Order $order, User $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['order.' . $this->order->id];
    }
}
