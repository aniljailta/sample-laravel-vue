<?php

namespace App\Http\Requests;

use App\Models\Payment;
use App\Http\Requests\Request;

class DeletePaymentRequest extends Request
{
    public $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        $paymentId = $this->route('payment');

        try {
            $payment = Payment::findOrFail($paymentId);
            $this->data['requestedPayment'] = $payment;

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
            //
        ];
    }
}
