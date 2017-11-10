<?php

namespace App\Validators;

use App\Validators\EnchantValidatorTrait;
use Fadion\ValidatorAssistant\ValidatorAssistant;

use App\Models\User;

class BillValidator extends ValidatorAssistant {

    use EnchantValidatorTrait;

    protected $messages = array(
        'is_valid_user' => 'User is not valid.'
    );

    public function customIsValidUser($attribute, $value, $parameters)
    {
        if ( $value === 'all' )
        {
            return true;
        }

        if ( is_numeric($value) && $value >= 1 )
        {
            try {
                $user = User::findOrFail($value);
                return true;
            } catch (ModelNotFoundException $e) {
                return false;
            }
        }

        return false;
    }
}