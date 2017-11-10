import apiLocations from 'src/api/locations'

const receiveLocations = function() {
    return apiLocations.get({
        query: {
            per_page: 9999,
            where: {
                is_active: 'yes',
                category: 'plant'
            }
        }
    })
}
export default {
    methods: {
        receiveLocations
    }
}