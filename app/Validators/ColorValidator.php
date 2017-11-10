<?php

namespace App\Validators;

use App\Models\BuildingModel;
use App\Validators\Validator;
use App\Validators\EnchantValidatorTrait;

class ColorValidator extends Validator {

    use EnchantValidatorTrait;

    protected $attributes = array(
        'allowable_models_id' => 'Allowable model'
    );
    
    protected $messages = array(
        "is_valid_allowable_models" => "Allowable models is not valid",
    );

    protected $filters = array(
        'use_body' => 'numeric',
        'use_trim' => 'numeric',
        'use_roof' => 'numeric'
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
        $is_valid = true;

        if ( count($value) === 0 || $value == 'null')
        {
            $is_valid = false;
            return $is_valid;
        }

        $buildingModels = BuildingModel::active()
            ->with('style')->whereHas('style', function ($query) {
                $query->where('is_active', 'yes');
            })->get()->pluck('name', 'id')->toArray();
        
        foreach ( $value as $model_id )
        {
            if ( $model_id == 'all') continue;
            if ( !isset($buildingModels[$model_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }
}