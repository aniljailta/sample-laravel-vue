<?php

namespace App\Http\Requests\Orders;

use Store;

use App\Models\Order;
use App\Http\Requests\Request;

class ChangeOrderRequest extends Request
{
    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $inputs = array_replace_recursive(parent::all(), [
            'order_uuid' => $this->route('order_uuid')
        ]);
        return $inputs;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too (dealer account)
        $id = $this->route('order_uuid');

        try {
            $order = Order::where('uuid', $id)->firstOrFail();
            Store::set('order', $order);

            return true;
        } catch (Exception $e) {
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
            'order_uuid' => ['required', 'uuid']
        ];
    }
}
