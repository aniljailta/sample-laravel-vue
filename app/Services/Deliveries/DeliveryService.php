<?php

namespace App\Services\Deliveries;

use App\Models\Delivery;

class DeliveryService
{
    /**
     * @param array $deliveryParams
     * @return Delivery
     */
    public function create(array $deliveryParams): Delivery
    {
        $delivery = new Delivery($deliveryParams);
        $delivery = (new GeoCodingService())->setDistance($delivery);
        $delivery->save();
        return $delivery;
    }

    /**
     * @param Delivery $delivery
     * @param array $deliveryParams
     * @return Delivery
     */
    public function update(Delivery $delivery, $deliveryParams): Delivery
    {
        $delivery = $delivery->fill($deliveryParams);
        $delivery = (new GeoCodingService())->setDistance($delivery);
        $delivery->save();
        return $delivery;
    }
}