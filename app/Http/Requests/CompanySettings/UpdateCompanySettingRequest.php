<?php

namespace App\Http\Requests\CompanySettings;

use App\Models\ManufacturerCompany;
use App\Models\RtoCompany;
use Store;
use Auth;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateCompanySettingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user() && Auth::user()->hasRole('administrator')) {
            return true;
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
        $company = Store::get('company');
        if ($company->role_id === 'manufacturer') {

            $rules = ManufacturerCompany::$rules;
            $rules['change_order_fee'][] = 'nullable';
            if ($this->get('delivery_dispatch', false) !== 'dispatch') {
                $rules['delivery_contact_email'][] = 'nullable';
                $rules['delivery_contact_name'][] = 'nullable';
                $rules['delivery_contact_phone'][] = 'nullable';
            }
        }

        if ($company->role_id === 'rto') {
            $rules = RtoCompany::$rules;
        }

        return $rules;
    }
}
