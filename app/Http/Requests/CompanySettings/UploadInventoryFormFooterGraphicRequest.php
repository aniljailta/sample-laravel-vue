<?php

namespace App\Http\Requests\CompanySettings;

use Auth;
use App\Http\Requests\Request;

class UploadInventoryFormFooterGraphicRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('administrator')) return false;

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
