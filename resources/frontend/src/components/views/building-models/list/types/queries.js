export default {
    searches: {
        is_active(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'is_active', 'inq'], statuses)
            return query
        },
        style_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'style_id', 'inq'], statuses)
            return query
        }
    }
}
