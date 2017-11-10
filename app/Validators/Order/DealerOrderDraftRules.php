<?php

namespace App\Validators\Order;

class DealerOrderDraftRules {

    /**
     * @param $rules
     * @param $key
     * @param $rule
     */
    private function attach(&$rules, $key, $rule) {
        $rule = is_array($rule) ? $rule : [$rule];

        if (array_key_exists($key, $rules)) {
            $rules[$key] = array_merge($rules[$key], $rule);
        } else {
            $rules[$key] = $rule;
        }
    }

    /**
     * @param array $rules
     * @param array $inputs
     * @return DealerOrderDraftRules
     */
    public function apply(array &$rules = [], array $inputs = []): DealerOrderDraftRules {
        $isGenerator = (isset($inputs['is_generator']) && $inputs['is_generator'] === true) ? true : false;

        $this->attach($rules, 'dealer_notes', 'nullable');
        $this->attach($rules, 'note_dealer', 'nullable');

        // $rulesDealer
        $this->attach($rules, 'dealer_id', 'required');
        $this->attach($rules, 'sales_person', 'nullable');

        // $rulesRto
        $this->attach($rules, 'rto_term', 'nullable');
        
        if ($isGenerator)
        {
            $this->attach($rules, 'type', 'required');
            $this->attach($rules, 'building_condition', 'required');
            $this->attach($rules, 'sale_type', 'required_if:building_condition,new');
            $this->attach($rules, 'serial', 'required_if:sale_type,dealer-inventory');
            $this->attach($rules, 'building_style', 'required_if:sale_type,custom-order');
            $this->attach($rules, 'building_dimension', 'required_if:sale_type,custom-order');
            $this->attach($rules, 'customer.first_name', 'required');
            $this->attach($rules, 'customer.last_name', 'required');
        } else
        {
            $this->attach($rules, 'type', 'nullable');
            $this->attach($rules, 'building_condition', 'nullable');
            $this->attach($rules, 'sale_type', 'nullable');
            $this->attach($rules, 'serial', 'required_if:sale_type,dealer-inventory');
            $this->attach($rules, 'building_style', 'nullable');
            $this->attach($rules, 'building_dimension', 'nullable');
            $this->attach($rules, 'customer.first_name', 'nullable');
            $this->attach($rules, 'customer.last_name', 'nullable');
        }

        // $rulesCustomOrder
        //$this->validator->addRule('custom_build_options', 'nullable|array|is_valid_build_options:empty');
        $this->attach($rules, 'building_package', 'nullable');

        // $rulesCustomer
        $this->attach($rules, 'customer.learning_about_us', 'nullable');
        $this->attach($rules, 'customer.learning_about_us_other', 'nullable');
        $this->attach($rules, 'customer.email', 'required_without_all:customer.first_name,customer.last_name');
        $this->attach($rules, 'customer.phone_number', 'nullable');
        $this->attach($rules, 'customer.address', 'nullable');
        $this->attach($rules, 'customer.city', 'nullable');
        $this->attach($rules, 'customer.state', 'nullable');
        $this->attach($rules, 'customer.zip', 'nullable');
        $this->attach($rules, 'customer.building_location_address', 'nullable');
        $this->attach($rules, 'customer.building_location_city', 'nullable');
        $this->attach($rules, 'customer.building_location_state', 'nullable');
        $this->attach($rules, 'customer.building_location_zip', 'nullable');

        // $rulesRenter
        $this->attach($rules, 'renter.property_ownership', 'nullable');

        $this->attach($rules, 'renter.landlord_full_name', 'nullable');
        $this->attach($rules, 'renter.landlord_phone_number', 'nullable');
        
        $this->attach($rules, 'customer.email', 'nullable');

        $this->attach($rules, 'renter.cell_phone_number2', 'nullable');
        $this->attach($rules, 'renter.home_phone_number', 'nullable');

        $this->attach($rules, 'renter.renter_employer', 'nullable');
        $this->attach($rules, 'renter.renter_employer_phone_number', 'nullable');
        $this->attach($rules, 'renter.renter_employer_phone_extension', 'nullable');
        $this->attach($rules, 'renter.renter_supervisor', 'nullable');
        $this->attach($rules, 'renter.renter_supervisor_occupation', 'nullable');

        $this->attach($rules, 'renter.co_renter_employer', 'nullable');
        $this->attach($rules, 'renter.co_renter_employer_phone_number', 'nullable');
        $this->attach($rules, 'renter.co_renter_employer_phone_extension', 'nullable');
        $this->attach($rules, 'renter.co_renter_supervisor', 'nullable');
        $this->attach($rules, 'renter.co_renter_supervisor_occupation', 'nullable');

        $this->attach($rules, 'renter.co_renter_first_name', 'nullable');
        $this->attach($rules, 'renter.co_renter_last_name', 'nullable');

        $this->attach($rules, 'renter.reference1_name', 'nullable');
        $this->attach($rules, 'renter.reference1_relationship', 'nullable');
        $this->attach($rules, 'renter.reference1_phone_number', 'nullable');
        $this->attach($rules, 'renter.reference1_address', 'nullable');
        $this->attach($rules, 'renter.reference1_city', 'nullable');
        $this->attach($rules, 'renter.reference1_state', 'nullable');
        $this->attach($rules, 'renter.reference1_zip', 'nullable');

        $this->attach($rules, 'renter.reference2_name', 'nullable');
        $this->attach($rules, 'renter.reference2_relationship', 'nullable');
        $this->attach($rules, 'renter.reference2_phone_number', 'nullable');
        $this->attach($rules, 'renter.reference2_address', 'nullable');
        $this->attach($rules, 'renter.reference2_city', 'nullable');
        $this->attach($rules, 'renter.reference2_state', 'nullable');
        $this->attach($rules, 'renter.reference2_zip', 'nullable');

        // $rulesPdfOrder
        $this->attach($rules, 'payment_type', 'nullable');
        $this->attach($rules, 'payment_method', 'nullable');
        $this->attach($rules, 'order_date', 'required');
        $this->attach($rules, 'ced.start', 'required_with:order_date');
        $this->attach($rules, 'ced.end', 'required_with:order_date');
        $this->attach($rules, 'transaction_id', 'nullable');
        $this->attach($rules, 'gross_buydown', 'nullable');
        $this->attach($rules, 'amount_received', 'nullable');

        $this->attach($rules, 'sales_tax_rate', 'nullable');
        $this->attach($rules, 'signature_method_id', 'nullable');

        return $this;
    }
}