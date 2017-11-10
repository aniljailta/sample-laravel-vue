<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use App\Http\Requests\Request;

class DeleteInvoiceRequest extends Request
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
        $invoiceId = $this->route('invoice');

        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $this->data['requestedInvoice'] = $invoice;

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
