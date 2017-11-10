import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
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
        name: 'Last Move Date',
        id: 'location_date',
        checked: true,
        order_by: 'last_location.created_at',
        query: {
            fields: ['last_location_id'],
            include: ['last_location'],
            leftJoinRelation: ['last_location'],
            group_by: ['last_location.created_at']
        }
    },
    {
        name: 'Building SN',
        id: 'building_sn',
        checked: true,
        order_by: 'serial_number',
        query: {
            fields: ['id', 'serial_number'],
            group_by: ['serial_number']
        }
    },
    {
        name: 'Dealer Location',
        id: 'dealer',
        checked: true,
        order_by: 'last_location.location_id',
        query: {
            fields: ['last_location_id'],
            include: ['last_location.location'],
            leftJoinRelation: ['last_location', 'last_location.location']
        }
    },
    {
        name: 'Retail',
        id: 'retail',
        checked: true,
        order_by: 'order.total_sales_price',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.total_sales_price']
        }
    },
    {
        name: 'Style',
        id: 'style_id',
        checked: false,
        order_by: 'building_model.style_id',
        query: {
            fields: ['building_model_id'],
            include: ['building_model', 'building_model.style'],
            leftJoinRelation: ['building_model', 'building_model.style'],
            group_by: ['building_model.style_id']
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
        checked: false,
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
        name: 'Last Move Date',
        id: 'location_date',
        value: {
            between: [moment().startOf('month').format('YYYY-MM-DD'), moment().endOf('month').format('YYYY-MM-DD')]
        },
        checked: false,
        query: {
            where: 'last_location.created_at'
        }
    },
    {
        name: 'Building SN',
        id: 'serial_numbers_values',
        value: null,
        checked: true
        // query by helper
    },
    {
        name: 'Dealer Location',
        id: 'dealer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Retail',
        id: 'retail',
        value: null,
        checked: false,
        query: {
            where: 'order.total_sales_price'
        }
    },
    {
        name: 'Model',
        id: 'model_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Building Package',
        id: 'building_package_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Style',
        id: 'style_id',
        value: null,
        checked: false
        // query by helper
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
