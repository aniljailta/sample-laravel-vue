<?php

namespace App\Http\Requests\ColorCatalog;

use App\Models\ColorCatalog;
use Illuminate\Foundation\Http\FormRequest;

class AddColorCatalogRequest extends FormRequest
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
        $rules = ColorCatalog::$rules;
        $rules['upload_files.0'] = ['mimes:jpeg,bmp,png,jpg'];
        if(!$this->hex && !empty($this->file())) unset($rules['hex']);
        if($this->hex && empty($this->file())) unset($rules['upload_files']);
        return $rules;
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }
}
