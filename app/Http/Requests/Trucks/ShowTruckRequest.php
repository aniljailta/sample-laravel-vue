<?php

namespace App\Http\Requests\Trucks;

use Illuminate\Foundation\Http\FormRequest;
use App\Validators\Validator;
use Request;

class ShowTruckRequest extends FormRequest
{

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
        return [
        ];
    }
}
