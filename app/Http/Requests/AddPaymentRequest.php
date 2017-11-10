<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddPaymentRequest extends Request
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
        $this->rules['invoice_id'] = 'required|numeric';
        $this->rules['paymentable_id'] = 'required|numeric';
        $this->rules['amount'] = 'required|numeric';
        $this->rules['payment_method'] = 'required|in:cash,check,credit_card,ach,wire_transfer';
        $this->rules['transaction_id'] = 'required|numeric';
        $this->rules['status'] = 'required|in:pending,complete,cancelled';
        return $this->rules;
    }
}
