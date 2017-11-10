<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;
use Validator;

class GenerateCompleteOrderDocumentRequest extends Request
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
            'id' => ['required', 'uuid'],
            'category_id' => ['in:complete_order_documents'],
        ];
    }
}
