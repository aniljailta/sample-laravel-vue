export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        },
        model_id(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building.building_model_id', 'inq'], models)
            return query
        },
        order_type(search) {
            let query = {}
            let values = search.value.split(',')
            _.set(query, ['where', 'sale_type', 'inq'], values)
            return query
        },
        dealer(search) {
            let query = {}
            let dealers = search.value.split(',')
            _.set(query, ['where', 'dealer_id', 'inq'], dealers)
            return query
        },
        sales_person(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'sales_person', 'like'], value)
            return query
        },
        customer(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'order_reference.first_name', 'like'], value)
            _.set(query, ['where', 'or', 'order_reference.last_name', 'like'], value)
            return query
        },
        serial_numbers_values(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building.serial_number', 'inq'], models)
            return query
        }
    }
}
