<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Validators\OrderValidator;

class GetInventoryFormRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $req = [
            'building_id' => $this->route('building'),
            'dealer_id' => Request::input('dealer_id')
        ];
        $this->validator = OrderValidator::make(Request::all() + $req);

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
        $this->validator->addRule('building_id', 'required|numeric|exists:buildings,id');
        $this->validator->addRule('dealer_id', 'required|is_valid_dealer');
        return $this->rules;
    }
}
