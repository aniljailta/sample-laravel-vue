export default {
    searches: {
        dealer(search) {
            let query = {}
            let dealers = search.value.split(',')
            _.set(query, ['leftJoinRelation'], ['locations', 'last_location.location'])
            _.set(query, ['where', 'last_location.location_id', 'inq'], dealers)
            return query
        },
        serial_numbers_values(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'serial_number', 'inq'], models)
            return query
        },
        model_id(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building_model_id', 'inq'], models)
            return query
        },
        building_package_id(search) {
            let query = {}
            let models = search.value.split(',')
            _.set(query, ['where', 'building_package_id', 'inq'], models)
            return query
        },
        style_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'building_model.style_id', 'inq'], statuses)
            return query
        }
    }
}
