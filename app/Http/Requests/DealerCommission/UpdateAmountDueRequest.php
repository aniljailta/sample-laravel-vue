<?php

namespace App\Http\Requests\DealerCommission;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

use Entrust;
use Store;

class UpdateAmountDueRequest extends Request
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
            'id'  => ['required', 'numeric', Rule::exists('dealer_commission')->where(MANUFACTURER_COMPANY_ID, Store::get('company')->id)],
            'amount_due' => ['required', 'numeric']
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
