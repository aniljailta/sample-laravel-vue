export default {
    searches: {
        date_sold(search) {
            let query = {}
            _.set(query, ['where', 'order.sale', 'created_at'], search.value)
            return query
        },
        customer(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'order.order_reference', 'first_name', 'like'], value)
            _.set(query, ['where', 'order.order_reference', 'or', 'last_name', 'like'], value)
            return query
        },
        serial_numbers(search) {
            let query = {}
            if (search.value === 'only_with_sn') _.set(query, ['where', 'serial_number', 'notnull'], 'true')
            if (search.value === 'only_without_sn') _.set(query, ['where', 'serial_number', 'isnull'], 'true')
            return query
        },
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'last_status.status_id', 'inq'], statuses)
            return query
        },
        model_id(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building_model_id', 'inq'], models)
            return query
        },
        building_package_id(search) {
            let query = {}
            let packages = search.value.split(',')
            _.set(query, ['where', 'building_package_id', 'inq'], packages)
            return query
        },
        location_category(search) {
            let query = {}
            let value = search.value
            _.set(query, ['where', 'last_location.location.category', '='], value)
            return query
        },
        location(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'last_location.location', 'name', 'like'], value)
            return query
        },
        style_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'building_model.style_id', 'inq'], statuses)
            return query
        },
        width(search) {
            let query = {}
            let values = search.value.split(',')
            _.set(query, ['where', 'width', 'inq'], values)
            return query
        },
        length(search) {
            let query = {}
            let values = search.value.split(',')
            _.set(query, ['where', 'length', 'inq'], values)
            return query
        },
        payment_type(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'order.payment_type', 'inq'], statuses)
            return query
        },
        dealer(search) {
            let query = {}
            let dealers = search.value.split(',')
            _.set(query, ['where', 'order.dealer.id', 'inq'], dealers)
            return query
        },
        serial_numbers_values(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'serial_number', 'inq'], models)
            return query
        },
        used(search) {
            let query = {}
            if (search.value === 'used') {
                _.set(query, ['where', 'used_rto_owner', '<>'], 0)
            } else if (search.value === 'new') {
                _.set(query, ['where', 'used_rto_owner', '='], 0)
            }
            return query
        },
        sold_status(search) {
            let query = {}
            if (search.value === 'sold') {
                _.set(query, ['where', 'sold_status', '='], 'sold')
            } else if (search.value === 'not_sold') {
                _.set(query, ['where', 'sold_status', 'isnull'], 'true')
            }
            return query
        },
        roof_color(search) {
            let query = {}
            let colors = search.value.split(',')
            _.set(query, ['where', 'roof.option_color.color_id', 'inq'], colors)
            return query
        },
        trim_color(search) {
            let query = {}
            let colors = search.value.split(',')
            _.set(query, ['where', 'trim.option_color.color_id', 'inq'], colors)
            return query
        },
        siding_color(search) {
            let query = {}
            let colors = search.value.split(',')
            _.set(query, ['where', 'siding.option_color.color_id', 'inq'], colors)
            return query
        }
    }
}
