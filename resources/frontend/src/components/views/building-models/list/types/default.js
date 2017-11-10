import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Model ID',
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
        name: 'Style',
        id: 'style_id',
        checked: true,
        order_by: 'style_id',
        query: {
            fields: ['style_id'],
            include: ['style'],
            leftJoinRelation: ['style'],
            group_by: ['style_id']
        }
    },
    {
        name: 'Width',
        id: 'width',
        checked: true,
        order_by: 'width',
        query: {
            fields: ['width']
        }
    },
    {
        name: 'Length',
        id: 'length',
        checked: true,
        order_by: 'length',
        query: {
            fields: ['length']
        }
    },
    {
        name: 'Wall Height',
        id: 'wall_height',
        checked: true,
        order_by: 'wall_height',
        query: {
            fields: ['wall_height']
        }
    },
    {
        name: 'Shell Price',
        id: 'shell_price',
        checked: true,
        order_by: 's_shell_price',
        query: {
            fn: {
                'sum.s_shell_price': 'shell_price'
            }
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
        // query: {
        //     where: 'is_active'
        // }
    },
    {
        name: 'Style',
        id: 'style_id',
        value: null,
        checked: false
        // query: {
        //     where: 'style_id'
        // }
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
