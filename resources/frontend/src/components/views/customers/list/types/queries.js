export default {
    searches: {
        email(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'email', 'like'], value)
            return query
        },
        name(search) {
            let query = {}
            let value = '%' + search.value + '%'
            _.set(query, ['where', 'first_name', 'like'], value)
            _.set(query, ['where', 'or', 'last_name', 'like'], value)
            return query
        }
    }
}
