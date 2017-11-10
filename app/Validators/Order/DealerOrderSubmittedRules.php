<?php

namespace App\Validators\Order;

class DealerOrderSubmittedRules {

    /**
     * @param $rules
     * @param $key
     * @param $rule
     */
    private function attach(&$rules, $key, $rule) {
        $rule = is_array($rule) ? $rule : [$rule];

        if (array_key_exists($key, $rules)) {
            $rules[$key] = array_merge($rules[$key], $rule);
        }
    }

    /**
     * @param array $rules
     * @param array $inputs
     * @return DealerOrderSubmittedRules
     */
    public function apply(array &$rules = [], array $inputs = []): DealerOrderSubmittedRules {

        // $rulesDealer
        $rules['id'] = ['uuid', 'required'];

        $this->attach($rules, 'dealer_notes', 'nullable');
        $this->attach($rules, 'note_dealer', 'nullable');

        $this->attach($rules, 'dealer_id', 'required');
        $this->attach($rules, 'sales_person', 'required');

        // $rulesRto
        $this->attach($rules, 'rto_term', 'required_if:payment_type,rto');

        $this->attach($rules, 'building_condition', 'required');
        $this->attach($rules, 'sale_type', 'required_if:building_condition,new');
        $this->attach($rules, 'serial', 'required_if:sale_type,dealer-inventory');

        // custom order
        $this->attach($rules, 'building_style', 'required_if:sale_type,custom-order');
        $this->attach($rules, 'building_dimension', 'required_if:sale_type,custom-order');
        $this->attach($rules, 'custom_build_options', 'required_if:sale_type,custom-order');

        // $rulesCustomOrder
        //$this->validator->addRule('custom_build_options', 'nullable|array|is_valid_build_options:empty');
        $this->attach($rules, 'building_package', 'nullable');

        // $rulesCustomer
        $this->attach($rules, 'customer.learning_about_us', 'required');
        $this->attach($rules, 'customer.learning_about_us_other', 'required_if:customer.learning_about_us,other');

        $this->attach($rules, 'customer.first_name', 'required');
        $this->attach($rules, 'customer.last_name', 'required');
        $this->attach($rules, 'customer.email', 'required');
        $this->attach($rules, 'customer.phone_number', 'required');
        $this->attach($rules, 'customer.address', 'required');
        $this->attach($rules, 'customer.city', 'required');
        $this->attach($rules, 'customer.state', 'required');
        $this->attach($rules, 'customer.zip', 'required');
        
        $this->attach($rules, 'customer.building_location_address', $this->getBuildingAddressRules($inputs));
        $this->attach($rules, 'customer.building_location_city', $this->getBuildingAddressRules($inputs));
        $this->attach($rules, 'customer.building_location_state', $this->getBuildingAddressRules($inputs));
        $this->attach($rules, 'customer.building_location_zip', $this->getBuildingAddressRules($inputs));

        // $rulesRenter
        $this->attach($rules, 'renter.property_ownership', 'required');
        
        $this->attach($rules, 'renter.landlord_full_name', $this->getLandLordRules($inputs));
        $this->attach($rules, 'renter.landlord_phone_number', $this->getLandLordRules($inputs));

        $this->attach($rules, 'customer.email', 'required_if:renter.email_instead_of_mail,1');

        $this->attach($rules, 'renter.cell_phone_number2', 'nullable');
        $this->attach($rules, 'renter.home_phone_number', 'nullable');



        $this->attach($rules, 'renter.co_renter_first_name', 'nullable');
        $this->attach($rules, 'renter.co_renter_last_name', 'nullable');

        $this->attach($rules, 'renter.renter_employer', 'required');
        $this->attach($rules, 'renter.renter_employer_phone_number', 'required');
        $this->attach($rules, 'renter.renter_employer_phone_extension', 'nullable');
        $this->attach($rules, 'renter.renter_supervisor', 'required');
        $this->attach($rules, 'renter.renter_supervisor_occupation', 'required');

        $this->attach($rules, 'renter.co_renter_employer', $this->getCoRenterRules($inputs));
        $this->attach($rules, 'renter.co_renter_employer_phone_number', $this->getCoRenterRules($inputs));
        $this->attach($rules, 'renter.co_renter_employer_phone_extension', 'nullable');
        $this->attach($rules, 'renter.co_renter_supervisor', $this->getCoRenterRules($inputs));
        $this->attach($rules, 'renter.co_renter_supervisor_occupation', $this->getCoRenterRules($inputs));

        $this->attach($rules, 'renter.reference1_name', 'required');
        $this->attach($rules, 'renter.reference1_relationship', 'required');
        $this->attach($rules, 'renter.reference1_phone_number', 'required');
        $this->attach($rules, 'renter.reference1_address', 'required');
        $this->attach($rules, 'renter.reference1_city', 'required');
        $this->attach($rules, 'renter.reference1_state', 'required');
        $this->attach($rules, 'renter.reference1_zip', 'required');

        $this->attach($rules, 'renter.reference2_name', 'required');
        $this->attach($rules, 'renter.reference2_relationship', 'required');
        $this->attach($rules, 'renter.reference2_phone_number', 'required');
        $this->attach($rules, 'renter.reference2_address', 'required');
        $this->attach($rules, 'renter.reference2_city', 'required');
        $this->attach($rules, 'renter.reference2_state', 'required');
        $this->attach($rules, 'renter.reference2_zip', 'required');

        // $rulesPdfOrder
        $this->attach($rules, 'payment_type', 'required');
        $this->attach($rules, 'order_date', 'required');
        $this->attach($rules, 'ced.start', 'required_with:order_date');
        $this->attach($rules, 'ced.end', 'required_with:order_date');
        $this->attach($rules, 'payment_method', 'required_with:amount_received');
        $this->attach($rules, 'transaction_id', 'required_if:payment_method,check,credit_card');
        $this->attach($rules, 'amount_received', 'required');

        $this->attach($rules, 'sales_tax_rate', 'nullable');
        $this->attach($rules, 'signature_method_id', 'required');

        return $this;
    }

    /**
     * @param array $inputs
     * @return string
     */
    private function getLandLordRules(array $inputs = []): string {
        if (array_get($inputs, 'renter.property_ownership') === 'rent') {
            return 'required_if:renter.property_ownership,rent';
        }

        return  'nullable';
    }

    /**
     * @param array $inputs
     * @return string
     */
    private function getBuildingAddressRules(array $inputs = []): string {
        if (array_get($inputs, 'customer.building_in_same_address') === false) {
            return 'required_if:customer.building_in_same_address,false';
        }

        return 'nullable';
    }

    /**
     * @param array $inputs
     * @return string
     */
    private function getCoRenterRules(array $inputs = []): string {
        // renter.co_renter_first_name,renter.co_renter_last_name
        $coRenterFirstName = array_get($inputs, 'renter.co_renter_first_name');
        $coRenterLastName = array_get($inputs, 'renter.co_renter_last_name');

        if ($coRenterFirstName || $coRenterLastName) {
            return 'required_with:renter.co_renter_first_name,renter.co_renter_last_name';
        }
        
        return 'nullable';
    }
}