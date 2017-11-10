<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Validators\BuildingValidator;

class GetWorkFormRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $req = [
            'building_id' => $this->route('building')
        ];
        $this->validator = BuildingValidator::make(Request::all() + $req);

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
        $this->validator->addRule('building_id', 'required|numeric|exists:orders,building_id');
        return $this->rules;
    }
}
