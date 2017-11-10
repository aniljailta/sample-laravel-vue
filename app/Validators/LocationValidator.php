<?php

namespace App\Validators;

use App\Validators\Validator as Validator;

class LocationValidator extends Validator {

    protected $messages = array(
        "is_valid_latitude" => "Latitude is not valid",
        "is_valid_longitude" => "Longitude is not valid",
    );

    /**
     * Validate Latitude
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidLatitude($attribute, $value, $parameters)
    {
        $validator = $this->instance();

        if (preg_match("/^-?([1-8]?\d(?:\.\d{1,})?|90(?:\.0{1,6})?)$/", $value)) {
            return true;
        }

        return false;
    }


    /**
     * Validate Longitude
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidLongitude($attribute, $value, $parameters)
    {
        $validator = $this->instance();

        if (preg_match("/^-?((?:1[0-7]|[1-9])?\d(?:\.\d{1,})?|180(?:\.0{1,})?)$/", $value)) {
            return true;
        }

        return false;
    }
}