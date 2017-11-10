export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        },
        priority_id(search) {
            let query = {}
            let priorities = search.value.split(',')
            _.set(query, ['where', 'priority_id', 'inq'], priorities)
            return query
        },
        start_location_id(search) {
            let query = {}
            let locations = search.value.split(',')
            _.set(query, ['where', 'start_location_id', 'inq'], locations)
            return query
        },
        end_location_id(search) {
            let query = {}
            let locations = search.value.split(',')
            _.set(query, ['where', 'end_location_id', 'inq'], locations)
            return query
        },
        trailer_id(search) {
            let query = {}
            let trailerids = search.value.split(',')
            _.set(query, ['where', 'trailer_id', 'inq'], trailerids)
            return query
        },
        truck_id(search) {
            let query = {}
            let truckids = search.value.split(',')
            _.set(query, ['where', 'truck_id', 'inq'], truckids)
            return query
        },
        driver_id(search) {
            let query = {}
            let driverids = search.value.split(',')
            _.set(query, ['where', 'driver_id', 'inq'], driverids)
            return query
        }
    }
}
