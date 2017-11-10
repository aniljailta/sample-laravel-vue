<?php

namespace App\Validators;

use App\Models\Option;
use App\Models\BuildingModel;
use App\Validators\Validator;

class OptionValidator extends Validator {

    use EnchantValidatorTrait;

    protected $attributes = array(
        'allowable_models_id' => 'Allowable model'
    );
    
    protected $messages = array(
        "is_valid_allowable_models" => "Allowable models is not valid",
    );

    /**
     * Validate allowable models
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidAllowableModels($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $isValid = true;

        if (count($value) === 0) return $isValid;

        $buildingModels = BuildingModel::all()->pluck('name', 'id')->toArray();

        foreach ($value as $modelId) {
            if ($modelId == 'all') continue;
            if (!isset($buildingModels[$modelId])) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}