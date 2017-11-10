<?php

namespace App\Http\Requests\PriceGroups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class PriceGroupRequest extends FormRequest
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
        $rules = [];
        $publish_date = Input::has('publish_date');
        $type = Input::get('type');
        if ($publish_date && $type == 'undefined') {
            $rules = [
                'publish_date' => 'required|date|after:yesterday'
            ];
        }
        if (!$publish_date) {
            $rules = [
                'name'     => 'required',
                'category' => 'required'
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'publish_date.after' => 'The publish date must be greater than or equal to today.'
        ];
    }
}
