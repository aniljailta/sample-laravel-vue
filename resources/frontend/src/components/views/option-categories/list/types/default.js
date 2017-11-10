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
        name: 'Name',
        id: 'name',
        checked: true,
        query: {
            fields: ['name'],
            group_by: ['name']
        }
    },
    {
        name: 'Group',
        id: 'group',
        checked: true,
        query: {
            fields: ['group']
        }
    },
    {
        name: 'Is Required',
        id: 'is_required',
        checked: true,
        query: {
            fields: ['is_required'],
            group_by: ['is_required']
        }
    },
    {
        name: 'Qty Limit',
        id: 'qty_limit',
        checked: true,
        query: {
            fields: ['qty_limit'],
            group_by: ['qty_limit']
        }
    },
    {
        name: 'Sort ID',
        id: 'sort_id',
        checked: true,
        order_by: 'sort_id',
        query: {
            fields: ['sort_id'],
            group_by: ['sort_id']
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
        name: 'Is Required',
        id: 'is_required',
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
