<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderCustomerWasUpdated;
use App\Services\Customers\CustomerService;

class CreateCustomerAccount
{
    /**
     * @var CustomerService
     */
    protected $customerService;

    /**
     * Create the event listener.
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Handle the event.
     * @param OrderCustomerWasUpdated $event
     * @return bool
     */
    public function handle(OrderCustomerWasUpdated $event)
    {
        $order = $event->order;
        $customer = $this->customerService->createCustomerFromOrder($order);
        $order->customer()->associate($customer);
        $order->save();
    }
}
