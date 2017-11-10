<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;
use App\Validators\OrderValidator;

class ShowOrderRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $routed = ['id' => $this->route('id')];
        $this->validator = OrderValidator::make(Request::all() + $routed);

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
        $this->validator->addRule('id', 'required|uuid|exists:orders,uuid');
        return $this->rules;
    }
}
