<?php

namespace App\Services\Orders;

use App\Models\Style;
use App\Models\Building;
use App\Models\BuildingModel;
use App\Models\Order;

use Carbon\Carbon;

class OrderFormService
{
    /**
     * @param array $inputs
     * @return Order
     */
    public function toModel(array $inputs): Order {}

    /**
     * @param array $inputs
     * @return Order
     */
    protected function initOrder(array $inputs): Order {}

    /**
     * @return Building
     */
    protected function initBuilding(): Building {
        $building = new Building;
        $buildingStyle = new Style;
        $buildingModel = new BuildingModel;
        $buildingModel->setRelation('style', $buildingStyle);
        $building->setRelation('building_model', $buildingModel);
        $building->setRelation('building_options', collect());
        return $building;
    }
}
