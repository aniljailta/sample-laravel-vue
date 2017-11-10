import apiReferences from 'src/api/order-references'

export default {
    data() {
        return {
            customerNamesLoading: false,
            customerNames: []
        }
    },
    methods: {
        fetchCustomers(searchKeyword, query = {}) {
            this.customerNamesLoading = true

            let searchQuery = '%' + searchKeyword + '%'
            _.set(query, 'per_page', 99999)
            _.set(query, ['where', 'first_name', 'like'], searchQuery)
            _.set(query, ['where', 'or', 'last_name', 'like'], searchQuery)

            const namesData = apiReferences.get({query})
            return Promise.resolve(namesData)
                .then(response => {
                    let customerNamesStructured = []
                    const searchKeywordUpper = searchKeyword.toLowerCase()
                    const dataNotStructured = response.body.data

                    for (let item of dataNotStructured) {
                        if (item.firstName && _.includes(item.firstName.toLowerCase(), searchKeywordUpper)) {
                            if (!_.includes(customerNamesStructured, { 'name': item.firstName })) {
                                customerNamesStructured.push({name: item.firstName})
                            }
                        }

                        if (item.lastName && _.includes(item.lastName.toLowerCase(), searchKeywordUpper)) {
                            if (!_.includes(customerNamesStructured, { 'name': item.lastName })) {
                                customerNamesStructured.push({name: item.lastName})
                            }
                        }
                    }

                    this.customerNamesLoading = false
                    this.customerNames = customerNamesStructured
                })
                .catch(response => {
                    this.$emit('data-failed', response)
                })
        }
    }
}
