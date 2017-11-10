<?php

namespace App\Http\Requests\Orders;

use Lang;
use App\Models\OrderReference;
use App\Http\Requests\Request;
use \Illuminate\Contracts\Validation\Validator as Validator;

class CustomerOrderRequest extends Request
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

    public function rules()
    {
        $rules = [];
        foreach (array_only(OrderReference::$rules, $this->getCustomerFields()) as $key => $value) {
            $rules['customer.' . $key] = $value;
        }

        $rules = array_merge_recursive($rules, [
            'customer.building_location_address' => $this->getBuildingAddressRules($this->validationData()),
            'customer.building_location_city' => $this->getBuildingAddressRules($this->validationData()),
            'customer.building_location_state' => $this->getBuildingAddressRules($this->validationData()),
            'customer.building_location_zip' => $this->getBuildingAddressRules($this->validationData()),
        ]);

        $rules['dealer_id'] = ['numeric', 'required'];
        $rules['building_package'] = ['numeric', 'nullable'];
        $rules['building_style'] = ['numeric'];
        $rules['building_dimension'] = ['numeric'];
        $rules['custom_build_options'] = ['array'];

        $rules['confirm_emailing'] = ['in:yes'];
        $rules['contact_type'] = ['required', 'in:phone,email'];
        $rules['contact_time'] = ['required', 'in:anytime,after_5pm,weekends_only'];

        return $rules;
    }

    /**
     * @param array $inputs
     * @return string
     */
    private function getBuildingAddressRules(array $inputs = []): string {
        if (array_get($inputs, 'customer.building_in_same_address') === true) {
            return 'nullable';
        }

        return 'required_unless:customer.building_in_same_address,true';
    }

    /**
     * @return array
     */
    private function getCustomerFields()
    {
        return [
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'address',
            'city',
            'state',
            'zip',
            'building_in_same_address',
            'building_location_address',
            'building_location_city',
            'building_location_state',
            'building_location_zip'
        ];
    }

    /**
     * @return mixed
     */
    public function attributes()
    {
        $langOrderReferences = Lang::get('order_references');

        $attributes = [
            'building_package' => trans('order_form.building_package'),
            'building_style' => trans('order_form.building_style'),
            'building_dimension' => trans('order_form.building_dimension'),
            'custom_build_options' => trans('order_form.custom_build_options'),
            'contact_type' => trans('orders.contact_type'),
            'contact_time' => trans('orders.contact_time'),
            'confirm_emailing' => trans('orders.confirm_emailing'),
        ];
        foreach (array_only($langOrderReferences, $this->getCustomerFields()) as $key => $value) {
            $attributes['customer.' . $key] = $value;
        }

        return $attributes;
    }

    /**
     * @param Validator $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        $response = [];
        $response['status'] = 'error';
        $response['payload'] = null;
        $response['message'] = $validator->getMessageBag()->all();
        return $response;
    }
}
