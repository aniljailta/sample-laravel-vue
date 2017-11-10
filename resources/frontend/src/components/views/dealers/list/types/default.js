import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Dealer ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },

    {
        name: 'Business Name',
        id: 'business_name',
        checked: true,
        order_by: 'business_name',
        query: {
            fields: ['business_name'],
            group_by: ['business_name']
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
        name: 'phone',
        id: 'phone',
        checked: true,
        order_by: 'phone',
        query: {
            fields: ['phone']
        }
    },
    {
        name: 'email',
        id: 'email',
        checked: true,
        order_by: 'email',
        query: {
            fields: ['email']
        }
    },
    {
        name: 'ein',
        id: 'ein',
        checked: true,
        order_by: 'ein',
        query: {
            fields: ['ein']
        }
    },
    {
        name: 'Tax rate',
        id: 'tax_rate',
        checked: true,
        order_by: 'tax_rate',
        query: {
            fields: ['tax_rate']
        }
    },
    {
        name: 'Commission rate',
        id: 'commission_rate',
        checked: true,
        order_by: 'commission_rate',
        query: {
            fields: ['commission_rate']
        }
    },
    {
        name: 'Deposit % for Cash Sales',
        id: 'cash_sale_deposit_rate',
        checked: true,
        order_by: 'cash_sale_deposit_rate',
        query: {
            fields: ['cash_sale_deposit_rate']
        }
    },
    {
        name: 'Location',
        id: 'location_id',
        checked: true,
        order_by: 'location.name',
        query: {
            fields: ['location_id'],
            include: ['location'],
            leftJoinRelation: ['location'],
            group_by: ['location_id']
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
