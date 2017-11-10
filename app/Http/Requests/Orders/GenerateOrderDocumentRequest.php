<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator as Validator;

class GenerateOrderDocumentRequest extends Request
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
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        return array_replace_recursive(parent::all(), $this->route()->parameters());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => ['alpha_dash'],
            'save' => ['boolean'],
            'order_uuid' => ['required', 'uuid'],
            'category_id' => ['in:unsigned_order_documents,building_configuration,neighbor_release,deposit_receipt,complete_order_documents,quote_forms,order_documents'],
        ];
    }

    public function wantsJson() {
        return true;
    }

    /**
     * @param Validator $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        return $validator->getMessageBag()->all();
    }
}
