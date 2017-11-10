<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;
use App\Validators\OrderValidator;

class SearchOrdersRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = OrderValidator::make();

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
        if (isset($this->id)) {
            $this->validator->addRule('id', 'required');
            return $this->rules;
        }
        $this->validator->addRule('customer[email]', 'required_without_all:customer_first_name,customer_last_name|email');
        $this->validator->addRule('customer[first_name]', 'regex:' . REGEX_NAME);
        $this->validator->addRule('customer[last_name]', 'regex:' . REGEX_NAME);
        $this->validator->addRule('dealer_id', 'required|is_valid_dealer');
        return $this->rules;
    }
}
