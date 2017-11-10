<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Store;
use Exception;
use Entrust;

use App\Http\Requests\Request;

class DeleteCustomerRequest extends Request
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
        
        $id = $this->route('customer');

        try {
            $item = Customer::findOrFail($id);
            Store::set('customer', $item);

            return true;
        } catch (Exception $e) {
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
