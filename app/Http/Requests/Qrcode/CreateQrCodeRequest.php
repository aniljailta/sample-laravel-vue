<?php

namespace App\Http\Requests\Qrcode;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

use Entrust;

/**
 * Class CreateQrCodeRequest.
 */
class CreateQrCodeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Entrust::hasRole('administrator')) return false;

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
            'expire_on'             => 'required|date|after:today',
            'type'                  => 'required|string',
            'building_id'           => 'required|integer',
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