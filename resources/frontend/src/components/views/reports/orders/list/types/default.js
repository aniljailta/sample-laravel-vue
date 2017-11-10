import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Order ID',
        id: 'order_id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
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
        name: 'Customer',
        id: 'customer',
        order_by: 'order_reference.first_name',
        query: {
            fields: ['reference_id'],
            include: ['order_reference'],
            leftJoinRelation: ['order_reference'],
            group_by: ['order_reference.first_name']
        },
        checked: true
    },
    {
        name: 'Order Type',
        id: 'order_type',
        checked: true,
        order_by: 'sale_type',
        query: {
            fields: ['sale_type'],
            group_by: ['sale_type']
        }
    },
    {
        name: 'Building SN',
        id: 'building_sn',
        checked: false,
        order_by: 'building.serial_number',
        query: {
            fields: ['building_id'],
            include: ['building'],
            leftJoinRelation: ['building'],
            group_by: ['building.serial_number']
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
        name: 'Retail',
        id: 'retail',
        checked: true,
        order_by: 'retail',
        query: {
            fn: {
                'sum.retail': 'total_sales_price'
            }
        }
    }
]

let searches = [
    {
        name: 'Order ID',
        id: 'order_id',
        value: null,
        checked: false,
        query: {
            where: 'id'
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        value: {
            between: [
                moment().startOf('month').format('YYYY-MM-DD HH:mm:ss'),
                moment().endOf('month').format('YYYY-MM-DD HH:mm:ss')
            ]
        },
        parse(value) {
            let between = this.value.between
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss'))
            return {between}
        },
        query: {
            where: 'created_at'
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        value: 'submitted',
        checked: true
        // query: {
        //     where: 'status_id'
        // }
    },
    {
        name: 'Customer',
        id: 'customer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Order Type',
        id: 'order_type',
        value: null,
        checked: false
        // query: {
        //     where: 'sale_type'
        // }
    },
    {
        name: 'Building SN',
        id: 'building_id',
        value: null,
        checked: false,
        query: {
            where: 'building.id'
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        value: null,
        checked: false,
        query: {
            where: 'dealer_id'
        }
    },
    {
        name: 'Retail',
        id: 'retail',
        value: null,
        checked: false,
        query: {
            where: 'total_sales_price'
        }
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        value: null,
        checked: false
        // query: {
        //     where: 'payment_type'
        // }
    },
    {
        name: 'Order Date',
        id: 'order_date',
        checked: false,
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        query: {
            where: 'order_date'
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
    },
    {
        name: 'Retail',
        id: 'totalRetail',
        type: 'money',
        checked: true,
        value: null,
        query: {
            aggregate: {
                'sum.totalRetail': 'retail'
            },
            fn: {
                'sum.retail': 'total_sales_price'
            }
        }
    }
]

export default {
    dimensions,
    searches,
    totals
}
