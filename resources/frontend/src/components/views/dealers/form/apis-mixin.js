import apiDealers from 'src/api/dealers'
import apiLocations from 'src/api/locations'

const receiveActiveFlags = function() {
    return apiDealers.activeFlags({})
}

const receiveLocations = function() {
    return apiLocations.get({
        query: {
            per_page: 9999,
            where: {
                is_active: 'yes',
                category: 'dealer'
            }
        }
    })
}

export default {
    methods: {
        receiveActiveFlags,
        receiveLocations
    }
}