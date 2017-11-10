<?php

namespace App\Validators;

use Store;

use App\Models\BuildingHistory;
use App\Models\BuildingStatus;
use App\Validators\Validator as Validator;

class BuildingHistoryValidator extends Validator {

    protected $messages = array(
        "is_not_first_building_history" => "You can not delete first building history item.",
        "is_last_building_history" => "You should select only last building history item.",
        "is_not_billed_building_history" => "You should select only not billed history item",
        "is_valid_building_status_priority" => "New building status is not valid.",
    );

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsNotBilledBuildingHistory($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingHistory = Store::get('requestedBuildingHistory');

        if ( !is_null($requestedBuildingHistory->bill_id) ) {
            return false;
        }

        return true;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsNotFirstBuildingHistory($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingHistory = Store::get('requestedBuildingHistory');

        $firstBuildingStatus = $building->first_status;
        if ( $firstBuildingStatus->id === $requestedBuildingHistory->id ) {
            return false;
        }

        return true;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsLastBuildingHistory($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingHistory = Store::get('requestedBuildingHistory');

        $lastBuildingStatus = $building->last_status;
        if ( $lastBuildingStatus->id === $requestedBuildingHistory->id ) {
            return true;
        }

        return false;
    }

    /**
     * Valid building status - is only equal or greater than current priority of building status
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidBuildingStatusPriority($attribute, $value, $parameters)
    {
        $building = Store::get('building');

        $buildingStatus = BuildingStatus::findOrFail($value);
        $building->load(['last_status.building_status']);
        $lastStatus = $building->last_status->building_status;

        $disallowedStatuses = BuildingStatus::getDisabledBuildingStatusByPriority($lastStatus->priority, NULL, $buildingStatus->type);
        $is_disallow = $disallowedStatuses->where('id', $value)->all();

        if ( $is_disallow ) {
            return false;
        }

        return true;
    }

}