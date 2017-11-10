export default {
    searches: {
        name(search) {
            let query = {}
            _.set(query, ['where', 'name'], search.value)
            return query
        },
        category(search) {
            let query = {}
            _.set(query, ['where', 'category'], search.value)
            return query
        },
        status(search) {
            let query = {}
            _.set(query, ['where', 'status'], search.value)
            return query
        }
    }
}
