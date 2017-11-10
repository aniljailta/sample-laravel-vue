<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddInvoiceRequest extends Request
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
        $this->rules['amount'] = 'required|numeric';
        $this->rules['invoiceable_id'] = 'required|numeric';
        $this->rules['invoice_number'] = 'required|string|regex:/^[\w-]*$/';
        $this->rules['status'] = 'required|string|in:open,closed';
        return $this->rules;
    }
}
