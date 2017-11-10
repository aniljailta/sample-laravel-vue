import apiDeliveries from 'src/api/deliveries'
import apiBuildings from 'src/api/buildings'
import apiLocations from 'src/api/locations'
import apiSales from 'src/api/sales'
import apiDrivers from 'src/api/users'
import apiTrucks from 'src/api/delivery-trucks'
import apiTrailers from 'src/api/delivery-trailers'

const receiveLocations = function () {
    return apiLocations.get({
        query: {
            fields: ['id', 'name'],
            per_page: 999999,
            order_by: ['name asc']
        }
    })
}

const receiveStatuses = function () {
    return apiDeliveries.statuses({})
}

const receiveCategories = function () {
    return apiDeliveries.categories({})
}

const receivePriorities = function () {
    return apiDeliveries.priorities({})
}

const receiveDrivers = function () {
    return apiDrivers.get({
        query: {
            fields: ['id', 'first_name', 'last_name'],
            per_page: 999999,
            order_by: ['id asc']
        }
    })
}

const receiveTrucks = function () {
    return apiTrucks.get({
        query: {
            fields: ['id', 'name'],
            per_page: 999999,
            order_by: ['id asc']
        }
    })
}

const receiveTrailers = function () {
    return apiTrailers.get({
        query: {
            fields: ['id', 'name'],
            per_page: 999999,
            order_by: ['id asc']
        }
    })
}

const receiveBuildings = function () {
    return apiBuildings.get({
        query: {
            fields: ['id', 'serial_number', 'last_location_id'],
            per_page: 999999,
            order_by: ['id asc'],
            include: {
                last_location: {
                    fields: ['id', 'location_id']
                }
            },
            leftJoinRelation: ['last_location']
        }
    })
}
const receiveSales = function () {
    return apiSales.get({
        query: {
            fields: ['id', 'order_id', 'building_id'],
            per_page: 999999,
            order_by: ['id asc'],
            include: {
                building: {
                    fields: ['id', 'serial_number', 'last_location_id'],
                    order_by: ['id asc']
                },
                'building.last_location': {
                    fields: ['id', 'location_id']
                },
                order: {
                  fields: ['id', 'ced_end', 'reference_id']
                },
                'order.order_reference': {
                    fields: ['id', 'first_name', 'last_name'],
                    order_by: ['id asc']
                }
            },
            leftJoinRelation: ['building', 'order', 'order.order_reference', 'building.last_location']
        }
    })
}
export default {
    methods: {
        receiveLocations,
        receiveStatuses,
        receiveBuildings,
        receiveCategories,
        receivePriorities,
        receiveSales,
        receiveTrucks,
        receiveTrailers,
        receiveDrivers
    }
}