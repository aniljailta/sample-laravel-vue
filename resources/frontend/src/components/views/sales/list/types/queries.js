export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        },
        serial_numbers_values(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building.serial_number', 'inq'], models)
            return query
        },
        order_type(search) {
            let query = {}
            let values = search.value.split(',')
            _.set(query, ['where', 'order.sale_type', 'inq'], values)
            return query
        },
        payment_type(search) {
            let query = {}
            let values = search.value.split(',')
            _.set(query, ['where', 'order.payment_type', 'inq'], values)
            return query
        },
        dealer(search) {
            let query = {}
            let dealers = search.value.split(',')
            _.set(query, ['where', 'order.dealer_id', 'inq'], dealers)
            return query
        },
        sales_person(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'order.sales_person', 'like'], value)
            return query
        },
        customer(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'order.order_reference', 'first_name', 'like'], value)
            _.set(query, ['where', 'order.order_reference', 'or', 'last_name', 'like'], value)
            return query
        },
        location(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'building.last_location.location', 'name', 'like'], value)
            return query
        },
        delivery_status(search) {
            let query = {}
            let dealers = search.value.split(',')
            _.set(query, ['where', 'delivery.status_id', 'inq'], dealers)
            return query
        }
    }
}
