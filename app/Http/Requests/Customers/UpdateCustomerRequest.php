<?php

namespace App\Http\Requests\Customers;

use Store;
use Entrust;

use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Http\Requests\Request;

class UpdateCustomerRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        if (!Entrust::hasRole('administrator')) return false;
        
        $itemID = $this->route('customer');

        try {
            $item = Customer::findOrFail($itemID);
            Store::set('customer', $item);

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
        $rules = array_merge_recursive(Customer::$rules, [
            'email' => [
                Rule::unique('customers', 'email')
                    ->whereNot('id', $this->id)
                    ->where(MANUFACTURER_COMPANY_ID, Store::get('company')->id)
            ],
        ]);

        return $rules;
    }
}
