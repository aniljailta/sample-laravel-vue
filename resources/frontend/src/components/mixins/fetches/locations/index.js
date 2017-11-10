import apiLocations from 'src/api/locations'

export default {
    data() {
        return {
            locationNamesLoading: false,
            locationNames: []
        }
    },
    methods: {
        fetchLocations(searchKeyword) {
            this.locationNamesLoading = true
            let query = {}
            let searchQuery = '%' + searchKeyword + '%'

            _.set(query, 'per_page', 99999)
            _.set(query, ['where', 'name', 'like'], searchQuery)

            const namesData = apiLocations.get({
                query
            })

            return Promise.resolve(namesData)
                .then(response => {
                    let customerNamesStructured = []
                    const dataNotStructured = response.body.data
                    for (let item of dataNotStructured) {
                        customerNamesStructured.push({name: item.name})
                    }
                    this.locationNamesLoading = false
                    this.locationNames = customerNamesStructured
                })
                .catch(response => {
                    this.$emit('data-failed', response)
                })
        }
    }
}
