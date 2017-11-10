<?php

namespace App\Http\Requests\Colors;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;

class AddColorRequest extends FormRequest
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
        $rules = Color::$rules;
        $rules['upload_files.0'] = ['mimes:jpeg,bmp,png,jpg'];
        if(!$this->hex && !empty($this->file())) unset($rules['hex']);
        if($this->hex && empty($this->file())) unset($rules['upload_files']);
        if(!$this->option_id) unset($rules['option_id']);
        return $rules;
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }
}
