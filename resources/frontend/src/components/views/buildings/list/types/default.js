import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Sort ID',
        id: 'sort_id',
        checked: true,
        order_by: 'sort_id',
        query: {
            fields: ['id', 'sort_id'],
            group_by: ['sort_id']
        }
    },
    {
        name: 'Building SN',
        id: 'serial_number',
        checked: true,
        order_by: 'serial_number',
        query: {
            fields: ['serial_number'],
            group_by: ['serial_number']
        }
    },
    {
        name: 'Build Status',
        id: 'build_status',
        checked: true,
        order_by: 'last_status.status_id',
        query: {
            fields: ['status_id'],
            include: ['last_status.building_status'],
            leftJoinRelation: ['last_status'],
            group_by: ['last_status.status_id']
        }
    },
    {
        name: 'Total Price',
        id: 'total_price',
        checked: true,
        order_by: 'b_total_price',
        query: {
            fields: ['total_price'],
            fn: {
                'sum.b_total_price': 'total_price'
            }
        }
    },
    {
        name: 'Order ID',
        id: 'order_id',
        checked: true,
        order_by: 'order_id',
        query: {
            fields: ['order_id'],
            include: ['order.files'],
            group_by: ['order_id']
        }
    },
    {
        name: 'Sale ID',
        id: 'sale_id',
        checked: true,
        order_by: 'order.sale.id',
        query: {
            fields: ['order_id'],
            include: ['order.sale', 'order.sale.building', 'order.sale.order.files'],
            leftJoinRelation: ['order.sale'],
            group_by: ['order.sale.id']
        }
    },
    {
        name: 'Customer',
        id: 'customer',
        checked: true,
        order_by: 'order.order_reference.first_name',
        query: {
            fields: ['order_id'],
            include: ['order.order_reference'],
            leftJoinRelation: ['order.order_reference'],
            group_by: ['order.reference_id']
        }
    },
    {
        name: 'Location',
        id: 'location',
        checked: true,
        order_by: 'last_location.location.name',
        query: {
            fields: ['last_location_id'],
            include: ['last_location.location'],
            leftJoinRelation: ['last_location.location'],
            group_by: ['last_location.location_id']
        }
    },
    {
        name: 'Date Sold',
        id: 'date_sold',
        checked: true,
        order_by: 'order.sale.created_at',
        query: {
            fields: ['order_id'],
            include: ['order.sale'],
            leftJoinRelation: ['order.sale'],
            group_by: ['order.sale.created_at']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: false,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    },
    {
        name: 'Style',
        id: 'style_id',
        checked: false,
        order_by: 'building_model.style_id',
        query: {
            fields: ['building_model_id'],
            include: ['building_model.style'],
            leftJoinRelation: ['building_model.style'],
            group_by: ['building_model.style_id']
        }
    },
    {
        name: 'Model',
        id: 'model_id',
        checked: false,
        order_by: 'building_model.name',
        query: {
            fields: ['building_model_id'],
            include: ['building_model'],
            leftJoinRelation: ['building_model'],
            group_by: ['building_model_id']
        }
    },
    {
        name: 'Building Package',
        id: 'building_package_id',
        checked: false,
        order_by: 'building_package.name',
        query: {
            fields: ['building_package_id'],
            include: ['building_package'],
            leftJoinRelation: ['building_package'],
            group_by: ['building_package_id']
        }
    },
    {
        name: 'Width',
        id: 'width',
        checked: false,
        order_by: 'width',
        query: {
            fields: ['width'],
            group_by: ['width']
        }
    },
    {
        name: 'Length',
        id: 'length',
        checked: false,
        order_by: 'length',
        query: {
            fields: ['length'],
            group_by: ['length']
        }
    },
    {
        name: 'Payment Type',
        id: 'payment_method',
        checked: false,
        order_by: 'order.payment_type',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.payment_type']
        }
    },
    {
        name: 'Amount Received',
        id: 'amount_received',
        checked: false,
        order_by: 'amount_received',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.amount_received'],
            fn: {
                'sum.amount_received': 'order.amount_received'
            }
        }
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: false,
        order_by: 'order.sale.invoice_id',
        query: {
            fields: ['order_id'],
            include: ['order.sale'],
            leftJoinRelation: ['order.sale'],
            group_by: ['order.sale.invoice_id']
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        checked: false,
        order_by: 'sales_tax',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.sales_tax': 'order.sales_tax'
            }
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        checked: false,
        order_by: 'order.dealer_id',
        query: {
            fields: ['order_id'],
            include: ['order.dealer'],
            leftJoinRelation: ['order.dealer'],
            group_by: ['order.dealer_id']
        }
    },
    {
        name: 'Plant ID',
        id: 'plant_id',
        checked: false,
        order_by: 'plant_id',
        query: {
            include: ['plant'],
            fields: ['plant_id'],
            group_by: ['plant_id']
        }
    },
    {
        name: 'Year of Manufacture',
        id: 'manufacture_year',
        checked: false,
        order_by: 'manufacture_year',
        query: {
            fields: ['manufacture_year'],
            group_by: ['manufacture_year']
        }
    },
    {
        name: 'Roof Color',
        id: 'roof_color',
        checked: false,
        order_by: 'roof_bo_id',
        query: {
            fields: ['roof_bo_id'],
            include: ['roof.option_color'],
            leftJoinRelation: ['roof.option_color']
        }
    },
    {
        name: 'Trim Color',
        id: 'trim_color',
        checked: false,
        order_by: 'trim_bo_id',
        query: {
            fields: ['trim_bo_id'],
            include: ['trim.option_color'],
            leftJoinRelation: ['trim.option_color']
        }
    },
    {
        name: 'Siding Color',
        id: 'siding_color',
        checked: false,
        order_by: 'siding_bo_id',
        query: {
            fields: ['siding_bo_id'],
            include: ['siding.option_color'],
            leftJoinRelation: ['siding.option_color']
        }
    },
    {
        name: 'Used',
        id: 'used',
        checked: false,
        order_by: 'used_rto_owner',
        query: {
            fields: ['used_rto_owner'],
            group_by: ['used_rto_owner']
        }
    },
    {
        name: 'Sold Status',
        id: 'sold_status',
        checked: false,
        order_by: 'sold_status',
        query: {
            fields: ['sold_status'],
            group_by: ['sold_status']
        }
    }
    /*
    {
        name: 'Delivery Date',
        id: 'delivery_date',
        checked: false,
        order_by: 'order.dealer_id',
        query: {
            include: ['order.dealer'],
            leftJoinRelation: ['order.dealer'],
            group_by: ['order.dealer_id']
        }
    }*/
]

let searches = [
    {
        name: 'Sort ID',
        id: 'sort_id',
        value: null,
        checked: false,
        query: {
            where: 'sort_id'
        }
    },
    {
        name: 'Building SN',
        id: 'serial_numbers',
        value: 'only_with_sn',
        checked: true,
        hidden: true
        // query by helper
    },
    {
        name: 'Build Status',
        id: 'status_id',
        value: '4',
        checked: true,
        query: {
            where: 'last_status.status_id'
        }
    },
    {
        name: 'Total Price',
        id: 'total_price',
        value: null,
        checked: false,
        query: {
            where: 'total_price'
        }
    },
    {
        name: 'Order ID',
        id: 'order_id',
        value: null,
        checked: false,
        query: {
            where: 'order_id'
        }
    },
    {
        name: 'Sale ID',
        id: 'sale_id',
        checked: false,
        query: {
            where: 'order.sale.id'
        }
    },
    {
        name: 'Customer',
        id: 'customer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Location Category',
        id: 'location_category',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Location',
        id: 'location',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Date Sold',
        id: 'date_sold',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
            return {between}
        },
        checked: false
        // query by helper
    },
    {
        name: 'Date Created',
        id: 'date_created',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
            return {between}
        },
        checked: false,
        query: {
            where: 'created_at'
        }
    },
    {
        name: 'Style',
        id: 'style_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Model',
        id: 'model_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Building Package',
        id: 'building_package_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Width',
        id: 'width',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Length',
        id: 'length',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Amount Received',
        id: 'amount_received',
        checked: false,
        query: {
            where: 'order.amount_received'
        }
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: false,
        query: {
            where: 'order.sale.invoice_id'
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        checked: false,
        query: {
            where: 'order.sales_tax'
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        value: null,
        checked: false
    },
    {
        name: 'Plant ID',
        id: 'plant_id',
        value: null,
        checked: false,
        query: {
            where: 'plant_id'
        }
    },
    {
        name: 'Year of Manufacture',
        id: 'manufacture_year',
        value: null,
        checked: false,
        query: {
            where: 'manufacture_year'
        }
    },
    {
        name: 'Buildings with SN',
        id: 'serial_numbers_values',
        value: null,
        checked: true
        // query by helper
    },
    {
        name: 'Used',
        id: 'used',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Sold Status',
        id: 'sold_status',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Roof Color',
        id: 'roof_color',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Trim Color',
        id: 'trim_color',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Siding Color',
        id: 'siding_color',
        value: null,
        checked: false
        // query by helper
    }
]

let totals = [
    {
        name: 'Rows',
        id: 'totalRows',
        type: 'unit',
        checked: null,
        selectable: false,
        value: null
    }
]

export default {
    dimensions,
    searches,
    totals
}
