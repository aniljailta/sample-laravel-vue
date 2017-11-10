<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;
use App\Models\Order;
use App\Models\OrderReference;

use App\Validators\Order\DealerOrderDraftRules;
use App\Validators\Order\DealerOrderSubmittedRules;
use Illuminate\Contracts\Validation\Validator as Validator;
use Lang;

class SaveDealerOrderRequest extends Request
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
     * @param DealerOrderDraftRules $dealerOrderDraftRules
     * @param DealerOrderSubmittedRules $dealerOrderSubmittedRules
     * @return array
     */
    public function rules(DealerOrderDraftRules $dealerOrderDraftRules, DealerOrderSubmittedRules $dealerOrderSubmittedRules)
    {
        $rules = Order::$rules;
        $rules['id'] = ['nullable', 'uuid'];
        $rules['save_as'] = ['in:existing,new'];
        $rules['order_date'] = ['date_format:m/d/Y'];

        if ($this->input('sale_type') === 'custom-order') {
            $rules['building_package'] = ['numeric', 'nullable'];
            $rules['building_style'] = ['numeric'];
            $rules['building_dimension'] = ['numeric'];
            $rules['custom_build_options'] = ['array'];
        }
        // $rules['serial'] = [''];

        $rules['delivery_remarks.level_pad'] = $rules['dr_level_pad'];
        $rules['delivery_remarks.soft_when_wet'] = $rules['dr_soft_when_wet'];
        $rules['delivery_remarks.width_restrictions'] = $rules['dr_width_restrictions'];
        $rules['delivery_remarks.height_restrictions'] = $rules['dr_height_restrictions'];
        $rules['delivery_remarks.requires_site_visit'] = $rules['dr_requires_site_visit'];
        $rules['delivery_remarks.must_cross_neighboring_prop'] = $rules['dr_must_cross_neighboring_prop'];
        $rules['delivery_remarks.notes'] = $rules['dr_notes'];

        foreach (array_only(OrderReference::$rules, $this->getCustomerFields()) as $key => $value) {
            $rules['customer.' . $key] = $value;
        }

        if ($this->input('payment_type') === 'rto') {
            foreach (array_except(OrderReference::$rules, $this->getCustomerFields()) as $key => $value) {
                $rules['renter.' . $key] = $value;
            }

        }

        if (!request('id') || request('status_id') !== 'submitted') {
            $dealerOrderDraftRules->apply($rules, Request::all());
        } else
            if (request('status_id') === 'submitted') {
                $dealerOrderSubmittedRules->apply($rules, Request::all());
            }

        return $rules;
    }

    /**
     * @return array
     */
    private function getCustomerFields()
    {
        return [
            'learning_about_us',
            'learning_about_us_other',
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
        $langOrders = Lang::get('orders');
        $langOrderReferences = Lang::get('order_references');

        $attributes = [
            'building_package' => trans('order_form.building_package'),
            'building_style' => trans('order_form.building_style'),
            'building_dimension' => trans('order_form.building_dimension'),
            'custom_build_options' => trans('order_form.custom_build_options')
        ];

        $attributes['delivery_remarks.level_pad'] = $langOrders['dr_level_pad'];
        $attributes['delivery_remarks.soft_when_wet'] = $langOrders['dr_soft_when_wet'];
        $attributes['delivery_remarks.width_restrictions'] = $langOrders['dr_width_restrictions'];
        $attributes['delivery_remarks.height_restrictions'] = $langOrders['dr_height_restrictions'];
        $attributes['delivery_remarks.requires_site_visit'] = $langOrders['dr_requires_site_visit'];
        $attributes['delivery_remarks.must_cross_neighboring_prop'] = $langOrders['dr_must_cross_neighboring_prop'];
        $attributes['delivery_remarks.notes'] = $langOrders['dr_notes'];

        foreach (array_only($langOrderReferences, $this->getCustomerFields()) as $key => $value) {
            $attributes['customer.' . $key] = $value;
        }

        if ($this->input('payment_type') === 'rto') {
            foreach (array_except($langOrderReferences, $this->getCustomerFields()) as $key => $value) {
                $attributes['renter.' . $key] = $value;
            }
        }

        array_merge($attributes, $langOrders);
        return $attributes;
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'customer.email.required_without_all' => trans('order_form.customer.email_required_if_no_names')
        ];
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
