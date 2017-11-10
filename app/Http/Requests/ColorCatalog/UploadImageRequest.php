<?php

namespace App\Http\Requests\ColorCatalog;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ColorCatalog;
use Store;

class UploadImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $colorID = $this->storable_id;

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
        return [
            'upload_files.0' => ['required', 'mimes:jpeg,bmp,png,jpg']
        ];
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }
}
