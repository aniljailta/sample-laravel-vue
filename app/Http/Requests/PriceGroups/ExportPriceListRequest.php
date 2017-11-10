<?php

namespace App\Http\Requests\PriceGroups;

use App\Models\PriceGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportPriceListRequest extends FormRequest
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
        $rules = PriceGroup::$rules;

        array_push($rules['id'], 'required', Rule::exists('price_groups', 'id'));
        array_push($rules['category'], 'required');

        return $rules;
    }
}
