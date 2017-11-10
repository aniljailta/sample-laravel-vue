import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        checked: true,
        order_by: 'order.date_submitted',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.date_submitted']
        }
    },
    {
        name: 'Sale ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Order ID',
        id: 'order_id',
        checked: true,
        order_by: 'order_id',
        query: {
            fields: ['order_id'],
            group_by: ['order_id']
        }
    },
    // {
    //     name: 'Date Created',
    //     id: 'date_created',
    //     checked: false,
    //     order_by: 'created_at',
    //     query: {
    //         fields: ['created_at'],
    //         group_by: ['created_at']
    //     }
    // },
    {
        name: 'Status',
        id: 'status_id',
        checked: true,
        order_by: 'status_id',
        query: {
            fields: ['status_id'],
            group_by: ['status_id']
        }
    },
    {
        name: 'Building SN',
        id: 'building_sn',
        checked: true,
        order_by: 'building.serial_number',
        query: {
            fields: ['building_id'],
            include: ['building'],
            leftJoinRelation: ['building'],
            group_by: ['building.serial_number']
        }
    },
    {
        name: 'Retail',
        id: 'retail',
        checked: true,
        order_by: 'retail',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.retail': 'order.total_sales_price'
            }
        }
    },
    {
        name: 'Order Type',
        id: 'order_type',
        checked: true,
        order_by: 'order.sale_type',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.sale_type']
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        checked: true,
        order_by: 'order.dealer_id',
        query: {
            fields: ['order_id'],
            include: ['order.dealer'],
            leftJoinRelation: ['order.dealer'],
            group_by: ['order.dealer_id']
        }
    },
    {
        name: 'Sales Person',
        id: 'sales_person',
        checked: true,
        order_by: 'order.sales_person',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.sales_person']
        }
    },
    {
        name: 'Customer',
        id: 'customer',
        order_by: 'order.order_reference.first_name',
        query: {
            fields: ['order_id'],
            include: ['order.order_reference'],
            leftJoinRelation: ['order.order_reference'],
            group_by: ['order.order_reference.first_name']
        },
        checked: true
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: true,
        order_by: 'invoice_id',
        query: {
            fields: ['invoice_id'],
            group_by: ['invoice_id']
        }
    },
    {
        name: 'Building Location',
        id: 'building_location',
        checked: false,
        order_by: 'building.last_location.location.id',
        query: {
            fields: ['building_id'],
            include: ['building.last_location.location'],
            leftJoinRelation: ['building.last_location.location'],
            group_by: ['building.last_location.location.id']
        }
    },
    {
        name: 'Delivery ID', // multiple join to the same table is wrong (multiple relations)
        id: 'delivery_id',
        checked: false,
        order_by: 'delivery.id',
        query: {
            fields: ['building_id'],
            include: ['delivery'],
            leftJoinRelation: ['delivery'],
            group_by: ['delivery.id']
        }
    },
    {
        name: 'Delivery Status',
        id: 'delivery_status_id',
        checked: false,
        order_by: 'delivery.status_id',
        query: {
            fields: ['building_id'],
            include: ['delivery'],
            leftJoinRelation: ['delivery'],
            group_by: ['delivery.status_id']
        }
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        checked: true,
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
        order_by: 'order.amount_received',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.amount_received']
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        checked: false,
        order_by: 'order.sales_tax',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.sales_tax']
        }
    },
    {
        name: 'Attachments',
        id: 'attachments',
        checked: true,
        query: {
            fields: ['order_id'],
            include: ['order.files'],
            leftJoinRelation: ['order']
        }
    }
]

let searches = [
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD')
            return {between}
        },
        checked: false,
        query: {
            where: 'order.date_submitted'
        }
    },
    {
        name: 'Sale ID',
        id: 'sale_id',
        value: null,
        checked: false,
        query: {
            where: 'id'
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
    // {
    //     name: 'Date Created',
    //     id: 'date_created',
    //     parse(value) {
    //         let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
    //         return {between}
    //     },
    //     checked: false,
    //     query: {
    //         where: 'created_at'
    //     }
    // },
    {
        name: 'Status',
        id: 'status_id',
        value: 'open,updated',
        checked: true
        // query by helper
    },
    {
        name: 'Building SN',
        id: 'serial_numbers_values',
        value: null,
        checked: true
        // query by helper
    },
    {
        name: 'Retail',
        id: 'retail',
        value: null,
        checked: false,
        query: {
            where: 'order.total_sales_price'
        }
    },
    {
        name: 'Order Type',
        id: 'order_type',
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
        name: 'Dealer',
        id: 'dealer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Sales Person',
        id: 'sales_person',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Customer',
        id: 'customer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: false,
        query: {
            where: 'invoice_id'
        }
    },
    {
        name: 'Building Location',
        id: 'location',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Delivery ID',
        id: 'delivery_id',
        checked: false,
        query: {
            where: 'delivery.id'
        }
    },
    {
        name: 'Delivery Status',
        id: 'delivery_status',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Order Date',
        id: 'order_date',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD')
            return {between}
        },
        checked: false,
        query: {
            where: 'order.order_date'
        }
    },
    {
        name: 'Amount Received',
        id: 'amount_received',
        value: null,
        checked: false,
        query: {
            where: 'order.amount_received'
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        value: null,
        checked: false,
        query: {
            where: 'order.sales_tax'
        }
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
