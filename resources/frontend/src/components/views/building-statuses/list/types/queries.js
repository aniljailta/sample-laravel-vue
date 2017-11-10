export default {
    searches: {
        is_active(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'is_active', 'inq'], statuses)
            return query
        },
        type(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'type', 'inq'], statuses)
            return query
        }
    }
}
