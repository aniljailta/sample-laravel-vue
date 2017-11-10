<?php

namespace App\Http\Requests\Orders;

use App\Models\Order;
use App\Http\Requests\Request;

use Illuminate\Contracts\Validation\Validator as Validator;
use Illuminate\Validation\Rule;
use Lang;

class UpdateOrderRequest extends Request
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
        $inputs = array_replace_recursive(parent::all(), [
            'uuid' => $this->get('id')
        ]);

        unset($inputs['id']);
        return $inputs;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Order::$rules;
        $rules['uuid'] = ['nullable', 'uuid', Rule::exists('orders')];
        return $rules;
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
