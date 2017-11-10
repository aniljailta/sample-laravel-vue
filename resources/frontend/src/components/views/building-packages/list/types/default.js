import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Package ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Model',
        id: 'building_model_id',
        checked: true,
        order_by: 'building_model_id',
        query: {
            fields: ['building_model_id'],
            include: ['building_model'],
            leftJoinRelation: ['building_model'],
            group_by: ['building_model_id']
        }
    },
    {
        name: 'Category',
        id: 'category_id',
        checked: true,
        order_by: 'category_id',
        query: {
            fields: ['category_id'],
            include: ['category'],
            leftJoinRelation: ['category'],
            group_by: ['category.name']
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
        name: 'Total Price',
        id: 'total_price',
        checked: true,
        order_by: 's_total_price',
        query: {
            fn: {
                'sum.s_total_price': 'total_price'
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
    },
    {
        name: 'Style',
        id: 'style_id',
        checked: false,
        order_by: 'building_model.style_id',
        query: {
            fields: ['building_model_id'],
            include: ['building_model.style'],
            leftJoinRelation: ['building_model.style'],
            group_by: ['building_model.style_id']
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
    },
    {
        name: 'Building Model',
        id: 'building_model_id',
        value: null,
        checked: false
        // query: {
        //     where: 'building_model_id'
        // }
    },
    {
        name: 'Category',
        id: 'category_id',
        value: null,
        checked: false
        // query: {
        //     where: 'category_id'
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
