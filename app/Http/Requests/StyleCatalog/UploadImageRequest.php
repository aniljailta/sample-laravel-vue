<?php

namespace App\Http\Requests\StyleCatalog;

use Store;
use App\Models\StyleCatalog;
use App\Http\Requests\Request;

class UploadImageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $styleID = $this->storable_id;

        try {
            $style = StyleCatalog::findOrFail($styleID);

            Store::set('style_catalog', $style);

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
