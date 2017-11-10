<?php

namespace App\Http\Requests\Customers;

use Entrust;
use Store;

use App\Models\Customer;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AddCustomerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Entrust::hasRole('administrator')) return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Customer::$rules;
        $rules = array_merge_recursive($rules, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip' => ['required'],
            'email' => [
                Rule::unique('customers', 'email')->where(MANUFACTURER_COMPANY_ID, Store::get('company')->id)
            ],
        ]);

        return $rules;
    }
}
