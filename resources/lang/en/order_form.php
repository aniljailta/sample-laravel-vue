<?php

return [
    /*
     * Messages
     */
    'dealer_id' => [
        'is_not_exists' => 'Dealer does not exist.'
    ],
    'customer' => [
        'email_required_if_no_names' => "The customer's email address is required if the customer's first and last name are not available."
    ],
    'building_style' => [
        'is_not_exists' => 'Building style does not exist.'
    ],
    'building_dimension' => [
        'is_not_exists' => 'Building dimension does not exist.'
    ],
    'building_package' => [
        'is_not_exists' => 'Building Package does not exist.'
    ],
    'dealer_inventory' => [
        'is_not_exists' => 'Dealer inventory does not exist.'
    ],
    'building_option' => [
        'is_not_exists' => 'Building option does not exist.'
    ],
    'order_option' => [
        'is_not_exists' => 'Order option does not exist.'
    ],
    'gross_buydown' => [
        'should_be_more_or_equal_min_gross_buydown' => 'Gross Buydown should more or equal $ :min_gross_buydown.'
    ],
    'amount_received' => [
        'should_be_more_or_equal_min_amount_amount' => "'Amount Received' field should equal or more than :amount."
    ],
    'attachments' => [
        'required_attachment_is_not_present' => "Required ':category' attachment is not present."
    ],

    /*
     * Labels
     */
    // dealer-order-form & customer-order-form
    'building_package' => 'Building Package',
    'building_style' => 'Building Style',
    'building_dimension' => 'Building Dimension',
    'custom_build_options' => 'Building Options',
];
