<?php

namespace App\Validators;

use Store;
use App\Validators\Validator;

class BuildingLocationValidator extends Validator {

    protected $messages = array(
        "is_not_first_building_location" => "You can not delete first building location.",
        "is_not_last_building_location" => "You can not change first building location.",
        "is_last_building_location" => "You should select only last building location.",
        "is_not_billed_building_location" => "You should select only not billed building location.",
    );

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsNotBilledBuildingLocation($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingLocation = Store::get('requestedBuildingLocation');

        if ( !is_null($requestedBuildingLocation->bill_id) )
        {
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
    protected function customIsNotFirstBuildingLocation($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingLocation = Store::get('requestedBuildingLocation');

        $firsBuildingLocation = $building->building_locations()->first();
        if ( $firsBuildingLocation->id === $requestedBuildingLocation->id )
        {
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
    protected function customIsNotLastBuildingLocation($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingLocation = Store::get('requestedBuildingLocation');

        if ( $building->last_location->id !== $requestedBuildingLocation->id )
        {
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
    protected function customIsLastBuildingLocation($attribute, $value, $parameters)
    {
        $building = Store::get('building');
        $requestedBuildingLocation = Store::get('requestedBuildingLocation');

        if ( $building->last_location->id !== $requestedBuildingLocation->id )
        {
            return false;
        }

        return true;
    }

}