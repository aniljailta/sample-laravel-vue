<?php

namespace App\Http\Requests\Customers;

use App\Http\Requests\Request;
use Entrust;

class IndexCustomerRequest extends Request
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
        return [
            'per_page' => ['numeric', 'min:1']
        ];
    }
}
