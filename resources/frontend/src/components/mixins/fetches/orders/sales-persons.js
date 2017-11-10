import apiOrders from 'src/api/orders'

export default {
    data() {
        return {
            salesPersonNames: [],
            salesPersonNamesLoading: false
        }
    },
    methods: {
        fetchSalesPerson(searchKeyword) {
            this.salesPersonNamesLoading = true
            let query = {}

            let searchQuery = '%' + searchKeyword + '%'
            _.set(query, 'per_page', 99999)
            _.set(query, ['where', 'sales_person', 'like'], searchQuery)
            const namesData = apiOrders.get({
                query
            })

            return Promise.resolve(namesData)
                .then(response => {
                    let salesPersonNamesStructured = []
                    const searchKeywordUpper = searchKeyword.toLowerCase()
                    const dataNotStructured = response.body.data

                    for (let item of dataNotStructured) {
                        if (!item.salesPerson) continue

                        if (_.includes(item.salesPerson.toLowerCase(), searchKeywordUpper)) {
                            if (!_.includes(salesPersonNamesStructured, {'name': item.salesPerson})) {
                                salesPersonNamesStructured.push({name: item.salesPerson})
                            }
                        }
                    }
                    this.salesPersonNamesLoading = false
                    this.salesPersonNames = salesPersonNamesStructured
                })
                .catch(response => {
                    this.$emit('data-failed', response)
                })
        }
    }
}
