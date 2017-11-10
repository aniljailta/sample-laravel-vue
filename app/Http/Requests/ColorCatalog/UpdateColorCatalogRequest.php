<?php

namespace App\Http\Requests\ColorCatalog;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ColorCatalog;
use Store;

class UpdateColorCatalogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $colorID = $this->route('color_catalog');

        try {
            $color = ColorCatalog::findOrFail($colorID);
            Store::set('color', $color);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
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
