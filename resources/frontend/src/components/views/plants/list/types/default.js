import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Plant ID',
        id: 'id',
        checked: true,
        order_by: 'plant_id',
        query: {
            fields: ['id', 'plant_id'],
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
        name: 'Description',
        id: 'description',
        checked: true,
        order_by: 'description',
        query: {
            fields: ['description']
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
