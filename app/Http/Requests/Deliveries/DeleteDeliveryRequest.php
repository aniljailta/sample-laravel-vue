<?php

namespace App\Http\Requests\Deliveries;

use App\Models\Delivery;
use Store;
use Entrust;

use App\Http\Requests\Request;
use App\Validators\Validator;

class DeleteDeliveryRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = Validator::make();

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        if (!Entrust::hasRole('administrator')) return false;

        $deliveryID = $this->route('delivery');

        try {
            $delivery = Delivery::findOrFail($deliveryID);
            Store::set('delivery', $delivery);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
