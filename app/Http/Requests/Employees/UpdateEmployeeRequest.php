<?php

namespace App\Http\Requests\Employees;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

use Entrust;

/**
 * Class AddEmployeeRequest.
 */
class UpdateEmployeeRequest extends Request
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
            'first_name'           => 'required|string|max:191',
            'last_name'            => 'required|string|max:191',
            'email'                => 'required|email|unique:users,email,'.$this->get('id')
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [ ];
    }
}
