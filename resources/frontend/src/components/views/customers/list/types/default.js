import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'First Name',
        id: 'first_name',
        checked: true,
        order_by: 'first_name',
        query: {
            fields: ['id', 'first_name']
        }
    },
    {
        name: 'Last Name',
        id: 'last_name',
        checked: true,
        order_by: 'last_name',
        query: {
            fields: ['id', 'last_name']
        }
    },
    {
        name: 'Email',
        id: 'email',
        checked: true,
        order_by: 'email',
        query: {
            fields: ['id', 'email']
        }
    },
    {
        name: 'Phone',
        id: 'phone',
        checked: true,
        order_by: 'phone',
        query: {
            fields: ['id', 'phone']
        }
    },
    {
        name: 'Address',
        id: 'address',
        checked: true,
        order_by: 'address',
        query: {
            fields: ['id', 'address']
        }
    },
    {
        name: 'City',
        id: 'city',
        checked: true,
        order_by: 'city',
        query: {
            fields: ['id', 'city']
        }
    },
    {
        name: 'State',
        id: 'state',
        checked: true,
        order_by: 'state',
        query: {
            fields: ['id', 'state']
        }
    },
    {
        name: 'Zip',
        id: 'zip',
        checked: true,
        order_by: 'zip',
        query: {
            fields: ['id', 'zip']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['id', 'created_at']
        }
    },
    {
        name: 'Date Updated',
        id: 'date_updated',
        checked: false,
        order_by: 'updated_at',
        query: {
            fields: ['id', 'updated_at']
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
        name: 'Date Updated',
        id: 'date_updated',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
            return {between}
        },
        checked: false,
        query: {
            where: 'updated_at'
        }
    },
    {
        name: 'Email',
        id: 'email',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Name',
        id: 'name',
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