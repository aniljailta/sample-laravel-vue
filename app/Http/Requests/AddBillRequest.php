<?php

namespace App\Http\Requests;

use App\Models\Bill;
use App\Http\Requests\Request;
use App\Validators\BillValidator;

class AddBillRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = BillValidator::make();

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('user_id', 'required|is_valid_user');
        return $this->rules;
    }
}
