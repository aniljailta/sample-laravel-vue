<?php

return [

    'id' => 'ID',
    'type' => 'Type',
    'uuid' => 'UUID',
    'status_id' => 'Status',
    'dealer_notes' => 'Dealer Notes', // dealer notes/descriptions
    'dealer_id' => 'Dealer', // foreign
    'reference_id' => 'Reference', // foreign
    'sales_person' => 'Sales Person',
    'sale_type' => 'Sale Type',
    'payment_type' => 'Payment Type',
    'building_condition' => 'Building Condition',
    'building_id' => 'Building ID', // foreign
    'building_serial' => 'Building Serial',
    'building_style_id' => 'Building Style', // foreign
    'building_model_id' => 'Building Model', // foreign
    'rto_type' => 'RTO Type',
    'rto_term' => 'RTO Term',
    'promo99' => 'Promo 99',
    'gross_buydown' => 'Gross buydown',
    'amount_received' => 'Amount Received',
    'payment_method' => 'Payment Method',
    'transaction_id' => 'Transaction ID',
    'delivery_charge' => 'Delivery Charge',
    'tax_delivery_charge' => 'Tax Delivery Charge',
    'dr_level_pad' => 'Delivery Remarks - Level Pad',
    'dr_soft_when_wet' => 'Delivery Remarks - Soft when wet',
    'dr_width_restrictions' => 'Delivery Remarks - Width Restrictions',
    'dr_height_restrictions' => 'Delivery Remarks - Height Restrictions',
    'dr_requires_site_visit' => 'Delivery Remarks - Requires site visit',
    'dr_must_cross_neighboring_prop' => 'Delivery Remarks - Must cross neighboring property',
    'dr_notes' => 'Delivery Remarks - Notes',
    'order_date' => 'Order Date',
    'ced_start' => 'From',
    'ced_end' => 'To',
    'signature_method' => 'Signature Method',

    // calculations
    'total_sales_price' => 'Total Sales Price',
    'deposit_amount' => 'Deposit Amount',
    'security_deposit' => 'Security Deposit',
    'net_buydown' => 'Net Buydown',
    'buydown_tax' => 'Buydown Tax',
    'balance' => 'Balance',
    'rto_amount' => 'RTO Amount',
    'rto_advance_monthly_renewal_payment' => 'RTO Advance Monthly Renewal Payment',
    'rto_sales_tax' => 'RTO Sales Tax',
    'rto_total_advanceMonthly_renewal_payment' => 'RTO Total Advance Monthly Renewal Payment',
    'rto_factor' => 'RTO Factor',
    'sales_tax' => 'Sales Tax',
    'total_amount_due' => 'Total Amount Due',
    'total_amount' => 'Total Amount',

    // Used in customer order form
    'contact_type' => 'Contact Type',
    'contact_type_phone' => 'Phone',
    'contact_type_email' => 'Email',
    
    'contact_time' => 'Contact Time',
    'contact_time_anytime' => 'Anytime',
    'contact_time_after_5pm' => 'After 5 p.m.',
    'contact_time_weekends_only' => 'Weekends only',

    'confirm_emailing' => 'Confirm Emailing',

    /*'values' => [
        'sale_type' => [
            'custom-order' => 'Custom',
            'dealer-inventory' => 'Dealer Inventory'
        ]
    ]*/

    'messages' => [
        'change_order_has_been_processed' => 'This is a change order and it has been processed. 
            The sale associated with the original order has been set to updated status and the building may have received a new serial number depending on the changes.
            Best practice suggests that manufacturing be updated as soon as possible.',
        'order_building_changed_to_draft' => 'The building associated with this order has been set to draft status. Be sure to have manufacturing remove from build que.',
        'order_building_in_production' => 'The building associated with this order is already in production.',
    ],
];
