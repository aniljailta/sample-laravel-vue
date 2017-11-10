export default {
    searches: {
        customer_id(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'customer.id', 'inq'], models)
            return query
        },
        date_submitted(search) {
            let query = {}
            let models = search.value.between
            _.set(query, ['where', 'order.date_submitted', 'between'], models)
            return query
        }
    }
}
