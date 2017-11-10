<?php

namespace App\Http\Requests\PriceGroups;

use App\Models\PriceGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportPriceListRequest extends FormRequest
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
        $rules                   = PriceGroup::$rules;
        $rules["upload_files.0"] = 'required|mimetypes:text/plain,text/csv,application/vnd.ms-excel,application/vnd.ms-office';
        // array_push($rules['id'], 'required', Rule::exists('price_groups', 'id'));
        array_push($rules['name'], 'required');
        array_push($rules['category'], 'required');
        return $rules;
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }
}
