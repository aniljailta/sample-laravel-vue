let dimensions = [
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at', 'id'],
            group_by: ['created_at']
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
        name: 'Status',
        id: 'status_id',
        value: 'open,updated',
        checked: true
        // query by helper
    }
]

export default {
    dimensions,
    searches
}
