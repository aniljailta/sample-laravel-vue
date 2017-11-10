let dimensions = [
    {
        name: 'Order status',
        id: 'status_id',
        checked: true,
        order_by: 'status_id',
        query: {
            fields: ['status_id', 'id'],
            group_by: ['status_id']
        }
    },
    {
        name: 'Customer name',
        id: 'customer',
        order_by: 'reference_id',
        query: {
            fields: ['reference_id'],
            include: ['order_reference'],
            leftJoinRelation: ['order_reference'],
            group_by: ['order_reference.first_name']
        },
        checked: true
    },
    {
        name: 'Total Building Price',
        id: 'total_sales_price',
        checked: true,
        order_by: 'total_sales_price',
        query: {
            fields: ['total_sales_price']
        }
    },
    {
        name: 'Purchase Method',
        id: 'payment_type',
        checked: true,
        order_by: 'payment_type',
        query: {
            fields: ['payment_type'],
            group_by: ['payment_type']
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        checked: true,
        order_by: 'dealer_id',
        query: {
            fields: ['dealer_id'],
            include: ['dealer'],
            leftJoinRelation: ['dealer'],
            group_by: ['dealer_id']
        }
    },
    {
        name: 'Sales Person',
        id: 'sales_person',
        checked: true,
        order_by: 'sales_person',
        query: {
            fields: ['sales_person'],
            group_by: ['sales_person']
        }
    },
    {
        name: 'Attachments',
        id: 'attachments',
        checked: true,
        query: {
            include: ['files']
        }
    }
]

let searches = [
    {
        name: 'Status',
        id: 'status_id',
        value: 'signed,signature_pending,review_needed',
        checked: true
    }
]

export default {
    dimensions,
    searches
}
