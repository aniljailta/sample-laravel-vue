import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Location ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Name',
        id: 'name',
        checked: true,
        order_by: 'name',
        query: {
            fields: ['name'],
            group_by: ['name']
        }
    },
    {
        name: 'Address',
        id: 'address',
        checked: true,
        query: {
            fields: ['address', 'country', 'city', 'state']
        }
    },
    {
        name: 'Category',
        id: 'category',
        checked: true,
        order_by: 'category',
        query: {
            fields: ['category'],
            group_by: ['category']
        }
    },
    {
        name: 'Is Active',
        id: 'is_active',
        checked: true,
        order_by: 'is_active',
        query: {
            fields: ['is_active'],
            group_by: ['is_active']
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
        name: 'Is Active',
        id: 'is_active',
        value: null,
        checked: false
    },
    {
        name: 'Category',
        id: 'category',
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