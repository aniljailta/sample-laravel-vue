export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        }
    }
}
