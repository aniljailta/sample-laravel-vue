<?php

namespace App\Http\Requests\PriceGroups;

use Illuminate\Foundation\Http\FormRequest;

class ImportPriceGroupRequest extends FormRequest
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
            'upload_files.0' => 'required|mimetypes:text/plain,text/csv,application/vnd.ms-excel,application/vnd.ms-office'
        ];
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }
}
