export default {
    searches: {
        is_required(search) {
            let query = {}
            if (search.value === 'yes') _.set(query, ['where', 'is_required', 'notnull'], 'true')
            if (search.value === 'no') _.set(query, ['where', 'is_required', 'isnull'], 'true')
            return query
        }
    }
}
