<?php

namespace App\Validators;

use App\Models\BuildingStatus;
use App\Models\Color;
use App\Models\ModelBuildingStatus;
use App\Models\Option;
use App\Validators\EnchantValidatorTrait;
use App\Validators\Validator;

class BuildingModelValidator extends Validator {

    use EnchantValidatorTrait;

    protected $attributes = array(
        'model_status_cost' => 'status default cost',
        'allowable_options_id' => 'Allowable option',
        'allowable_colors_id' => 'Allowable color'
    );

    protected $messages = array(
        "is_valid_model_status_cost" => "Some of default statuses is not valid",
        "is_valid_allowable_options" => "Allowable options is not valid",
        "is_valid_allowable_colors" => "Allowable colors is not valid",
    );

    /**
     * Validate allowable options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidAllowableOptions($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $options = Option::get()->pluck('name', 'id')->toArray();
        foreach ( $value as $option_id )
        {
            if ( $option_id == 'all') continue;
            if ( !isset($options[$option_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }

    /**
     * Validate allowable options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidAllowableColors($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $options = Color::get()->pluck('name', 'id')->toArray();
        foreach ( $value as $color_id )
        {
            if ( $color_id == 'all') continue;
            if ( !isset($options[$color_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }
}