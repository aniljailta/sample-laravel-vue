<?php

namespace App\Http\Requests\Qrcode;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class UploadFile.
 */
class UploadFile extends Request
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
            'building_id'  => 'required|integer',
            'status_id'  => 'required|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [ ];
    }
}