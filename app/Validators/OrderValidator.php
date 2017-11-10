<?php

namespace App\Validators;

use App\Models\Dealer;
use App\Validators\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Store;

class OrderValidator extends Validator {
    use EnchantValidatorTrait;

    /**
     * Validate dealer id against data file
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function customIsValidDealer($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        try {
            $dealer = Dealer::findOrFail($value);
            Store::set('dealer', $dealer);
        } catch(ModelNotFoundException $e ) {
            $validator->getMessageBag()->add('dealer_id', 'Dealer is not valid.');
            return false;
        }

        return true;
    }
}