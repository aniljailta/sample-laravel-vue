<?php

namespace App\Repositories;

use App\Models\OrderContact;

class OrderContactsRepository
{
    protected $orderContact;

    public function __construct()
    {
        $this->orderContact = new OrderContact();
    }

    /**
     * @param $data
     * @return OrderContact
     */
    public function storeOrderContact($data): OrderContact
    {
        return $this->orderContact->create($data);
    }

    /**
     * @param OrderContact $orderContact
     * @return OrderContact
     */
    public function saveOrderContact(OrderContact $orderContact): OrderContact
    {
        $orderContact->save();
        return $orderContact;
    }

    public function updateOrderContact($data, $id): bool
    {
        return $this->orderContact
            ->where('order_id', $id)
            ->orderBy('created_at', 'DESC')
            ->first()
            ->update($data);
    }

    public function getOrderContactByCustomerId($customer_id)
    {
        return $this->orderContact
            ->where('customer_id', $customer_id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getOrderContactByOrderId($order_id)
    {
        return $this->orderContact
            ->where('order_id', $order_id)
            ->orderBy('id', 'desc')
            ->first();
    }
}