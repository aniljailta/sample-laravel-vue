<?php

namespace App\Http\Requests\Qrcode;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class UpdateStatusRequest.
 */
class UpdateStatusRequest extends Request
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
            'identifier'  => 'required|string|size:30',
            'building_id'  => 'required|integer',
            'status_id'  => 'required|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'identifier.size'        => 'Invalid Identifier' ];
    }
}