<?php

namespace App\Validators;

use Store;

use App\Models\Option;

use App\Validators\EnchantValidatorTrait;
use App\Validators\Validator;

class BuildingValidator extends Validator {

    use EnchantValidatorTrait;

    protected $attributes = array(
        'building_id' => 'Building ID',
    );
    
    protected $messages = array(
        "is_valid_options" => "Option is not valid",
        "is_valid_import_building_models" => "Importing building models is not valid.",
        "is_valid_building" => "",
    );

    /**
     * Validate options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidOptions($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $isValid = true;

        if (count($value) === 0) {
            $isValid = false;
            return $isValid;
        }

        $allowableOptions = Option::active()->get()->pluck('name', 'id')->toArray();
        foreach ( $value as $option )
        {
            // check options as is
            if( !isset($option['id']) || !isset($allowableOptions[$option['id']]) ) {
                $validator->getMessageBag()->add('is_valid_options', 'Specified option is not exists.');
                $isValid = false;
                continue; // not found in db =(
            }

            // check prices
            if (!isset($option['unit_price']) || !is_numeric($option['unit_price'])) {
                $validator->getMessageBag()->add('is_valid_options', 'Specified option prices is not valid.');
                $isValid = false;
            }

            if (!isset($option['quantity']) || !is_numeric($option['quantity'])) {
                $validator->getMessageBag()->add('is_valid_options', 'Quantity of specified option is not valid.');
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * Validate options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidImportBuildingModels($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) >= 1 )
        {
            $is_valid = false;
        }

        foreach ( $value as $undefinedModel )
        {
            $validator->getMessageBag()->add('is_valid_import_building_models', "Undefined building model with params:
            style short code:{$undefinedModel['style_short_code']}, 
            width: {$undefinedModel['width']}, 
            length: {$undefinedModel['length']}, 
            wall_height: {$undefinedModel['height']}.");
        }

        return $is_valid;
    }

    /**
     * Validate options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidBuilding($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $isValid = true;

        $building = Store::get('building');
        $order = $building->current_order;
        
//        if ($order && $order->status_id === 'sale_generated') {
//            $validator->getMessageBag()->add('is_valid_building', 'Building is not editable (sale generated).');
//            $isValid = false;
//        }
//        todo ask Sergey about this change

        return $isValid;
    }
}