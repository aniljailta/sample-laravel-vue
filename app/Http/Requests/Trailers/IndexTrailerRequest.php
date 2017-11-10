<?php

namespace App\Http\Requests\Trailers;

use App\Models\Truck;
use Illuminate\Foundation\Http\FormRequest;
use Store;

use Request;
use App\Validators\Validator;

class IndexTrailerRequest extends FormRequest
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
        return [];
    }
}
