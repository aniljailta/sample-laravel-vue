import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Payment ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Invoice ID',
        id: 'invoice_id',
        checked: true,
        order_by: 'invoice_id',
        query: {
            fields: ['invoice_id'],
            group_by: ['invoice_id']
        }
    },
    {
        name: 'Payee ID',
        id: 'paymentable_id',
        checked: true,
        order_by: 'paymentable_id',
        query: {
            fields: ['paymentable_id'],
            group_by: ['paymentable_id']
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
        name: 'Payment Method',
        id: 'payment_method',
        checked: true,
        order_by: 'payment_method',
        query: {
            fields: ['payment_method'],
            group_by: ['payment_method']
        }
    },
    {
        name: 'Transaction ID',
        id: 'transaction_id',
        checked: true,
        order_by: 'transaction_id',
        query: {
            fields: ['transaction_id'],
            group_by: ['transaction_id']
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
        name: 'Payment ID',
        id: 'payment_id',
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
        name: 'Invoice',
        id: 'invoice_id',
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