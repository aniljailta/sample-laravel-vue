let dimensions = [
    {
        name: 'Date',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
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
        name: 'Customer Name',
        id: 'customer',
        order_by: 'customer.first_name',
        query: {
            fields: ['customer_id'],
            include: ['customer'],
            leftJoinRelation: ['customer'],
            group_by: ['customer.first_name']
        },
        checked: true
    },
    {
        name: 'Initial Contact',
        id: 'initial_contact',
        checked: true,
        order_by: 'initial_contact',
        query: {
            fields: ['initial_contact'],
            group_by: ['initial_contact']
        }
    },
    {
        name: 'Expiration Date',
        id: 'date_expiration',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    },
    {
        name: 'Order Submitted',
        id: 'order_submit',
        checked: true,
        order_by: 'order_submit',
        query: {
            fields: ['order_submit'],
            group_by: ['order_submit']
        }
    },
    {
        name: 'Total Price',
        id: 'total_price',
        order_by: 'building.order_id',
        checked: true,
        query: {
            fields: ['order_id'],
            include: ['building'],
            leftJoinRelation: ['building'],
            group_by: ['building.order_id']
        }
    },
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        order_by: 'order.date_submitted',
        checked: true,
        query: {
            fields: ['date_submitted'],
            include: ['order'],
            leftJoinRelation: ['order']
        }
    }
]

    let searches = [
    {
        name: 'Date Created',
        id: 'date_created',
        value: null,
        checked: false,
        query: {
            where: 'created_at'
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
        name: 'Customer Name',
        id: 'customer_id',
        value: null,
        checked: false,
        query: {
            where: 'customer_id'
        }
    },
    {
        name: 'Initial Contact',
        id: 'initial_contact',
        value: null,
        checked: false,
        query: {
            where: 'initial_contact'
        }
    },
    {
        name: 'Order Submitted',
        id: 'order_submit',
        value: null,
        checked: false,
        query: {
            where: 'order_submit'
        }
    },
    {
        name: 'Total Price',
        id: 'total_price',
        value: null,
        checked: false,
        query: {
            where: 'building.total_price',
            fields: ['order_id'],
            include: ['building'],
            leftJoinRelation: ['building']
        }
    },
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        value: null,
        checked: false,
        query: {
            where: 'date_submitted'
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
