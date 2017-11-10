export default {
    searches: {
        first_name(search) {
            let query = {}
            _.set(query, ['where', 'first_name'], search.value)
            return query
        },
        email(search) {
            let query = {}
            _.set(query, ['where', 'email'], search.value)
            return query
        }
    }
}
