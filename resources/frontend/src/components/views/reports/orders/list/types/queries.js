export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        },
        order_type(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'sale_type', 'inq'], statuses)
            return query
        },
        payment_type(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'payment_type', 'inq'], statuses)
            return query
        },
        dealer(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'dealer_id', 'inq'], statuses)
            return query
        },
        customer(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'order_reference.first_name', 'like'], value)
            _.set(query, ['where', 'or', 'order_reference.last_name', 'like'], value)
            return query
        }
    }
}
