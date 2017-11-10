export default {
    searches: {
        is_active(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'is_active', 'inq'], statuses)
            return query
        },
        category_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'category_id', 'inq'], statuses)
            return query
        }
    }
}
