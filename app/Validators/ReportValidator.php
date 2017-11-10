<?php

namespace App\Validators;

use App\Validators\EnchantValidatorTrait;
use Fadion\ValidatorAssistant\ValidatorAssistant;

use Helper;
use App\Models\User;

class ReportValidator extends ValidatorAssistant {

    use EnchantValidatorTrait;

    // Default rules
    protected $rules = array(

    );

    // Scope: Bill rules
    protected $rulesBills = array(
        'dimensions' => 'array|valid_dimensions:bills',

        'conditions[date]' => 'valid_date_condition:custom_datepicker',
        'conditions[user_id]' => 'numeric|exists:users,id',
        'conditions[building_id]' => 'numeric|exists:buildings,id'
    );

    // Scope: Expenses rules
    protected $rulesExpenses = array(
        'dimensions' => 'array|valid_dimensions:expenses',

        'conditions[date]' => 'valid_date_condition:custom_datepicker',
        'conditions[user_id]' => 'numeric|exists:users,id',
        'conditions[expense_type]' => 'in:location,build_status,sale_status',
        'conditions[bill_id]' => 'numeric|exists:bills,id',
        'conditions[building_id]' => 'numeric|exists:buildings,id'
    );

    protected $messages = array(
        'valid_dimensions' => 'Dimensions is not valid.'
    );

    // Custom attributes
    protected $attributes = array(
        'dimensions' => '"Dimensions"',
        'conditions[date]' => '"Date" condition',
        'conditions[user_id]' => '"User" condition',
        'conditions[expense_type]' => '"Expense Type" condition',
        'conditions[bill_id]' => '"Bill Number" condition',
        'conditions[building_id]' => '"Building" condition'
    );

    public function customValidDimensions($attribute, $value, $parameters)
    {
        if ( $attribute == 'bills' )
        {

        }

        if ( $attribute == 'expenses' )
        {

        }

        return true;
    }

    /**
     * Validate date (start + end) + custom (datepicker format)
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function customValidDateCondition($attribute, $value, $parameters)
    {
        Helper::load('Date');

        $validator = $this->instance();
        if ( !is_array($value) || !isset($value['start']) || !isset($value['end']) )
        {
            return false;
        }

        if ( array_keys($value) !== ['start', 'end'] )
        {
            return false;
        }

        foreach($value as $index => $date)
        {
            if ( $attribute === 'custom_datepicker' )
            {
                $date = date_from_custom_datepicker($date);

                if (($timestamp_start = $date) === false) {
                    $validator->getMessageBag()->add('conditions[date]', 'Incorrect "'.$index.'" date format');
                    return false;
                } else
                    return true;
            }

            if (($timestamp_start = strtotime($date)) === false) {
                $validator->getMessageBag()->add('conditions[date]', 'Incorrect "'.$index.'" date format');
                return false;
            }
        }

        return true;
    }
}