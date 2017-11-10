import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Invoice ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Customer ID',
        id: 'invoiceable_id',
        checked: true,
        order_by: 'invoiceable_id',
        query: {
            fields: ['invoiceable_id'],
            group_by: ['invoiceable_id']
        }
    },
    {
        name: 'Amount',
        id: 'amount',
        checked: true,
        order_by: 'amount',
        query: {
            fields: ['amount'],
            group_by: ['amount']
        }
    },
    {
        name: 'Invoice Number',
        id: 'invoice_number',
        checked: true,
        order_by: 'invoice_number',
        query: {
            fields: ['invoice_number'],
            group_by: ['invoice_number']
        }
    },
    {
        name: 'Status',
        id: 'status',
        checked: true,
        order_by: 'status',
        query: {
            fields: ['status'],
            group_by: ['status']
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
    }
]

let searches = [
    {
        name: 'Invoice ID',
        id: 'invoice_id',
        value: null,
        checked: false,
        query: {
            where: 'id'
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        value: {
            between: [
                moment().startOf('month').format('YYYY-MM-DD HH:mm:ss'),
                moment().endOf('month').format('YYYY-MM-DD HH:mm:ss')
            ]
        },
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss'))
            return {between}
        },
        checked: false,
        query: {
            where: 'created_at'
        }
    },
    {
        name: 'Customer',
        id: 'customer_id',
        value: null,
        checked: false
    },
    {
        name: 'Status',
        id: 'status',
        value: null,
        checked: false
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