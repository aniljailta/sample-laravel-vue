import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: '',
        id: 'move',
        checked: true,
        query: {}
    },
    {
        name: 'Sort ID',
        id: 'sort_id',
        checked: true,
        query: {
            fields: ['sort_id', 'id'],
            group_by: ['sort_id']
        }
    },
    {
        name: 'Type',
        id: 'type',
        checked: true,
        query: {
            fields: ['type'],
            group_by: ['type']
        }
    },
    {
        name: 'Name',
        id: 'name',
        checked: true,
        query: {
            fields: ['name'],
            group_by: ['name']
        }
    },
    {
        name: 'Hex',
        id: 'hex',
        checked: true,
        query: {
            fields: ['hex'],
            group_by: ['hex']
        }
    },
    {
        name: 'Is Active',
        id: 'is_active',
        checked: true,
        query: {
            fields: ['is_active'],
            group_by: ['is_active']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
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