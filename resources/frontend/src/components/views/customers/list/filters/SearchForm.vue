<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_updated'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-async-text v-if="search.id === 'name'"
                                        :is-loading="customerNamesLoading"
                                        :autocomplete-values="customerNames"
                                        @fetch-autocomplete="fetchNames"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-async-text v-if="search.id === 'email'"
                                        :is-loading="customerEmailsLoading"
                                        :autocomplete-values="customerEmails"
                                        @fetch-autocomplete="fetchEmails"
                                        v-bind:item="search">
                </form-search-async-text>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    import FormSearchAsyncText from 'src/components/views/_base/ListItems/search-forms/AsyncInput.vue'

    import apiCustomers from 'src/api/customers'

    export default {
        extends: baseSearchForm,
        mixins: [],
        data() {
            return {
                customerNamesLoading: false,
                customerEmailsLoading: false,
                customerNames: [],
                customerEmails: []
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchText,
            FormSearchAsyncText
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = []

                return Promise.all(datas)
                    .then(response => {
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            fetchNames(searchKeyword, query = {}) {
                this.customerNamesLoading = true

                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, 'per_page', 99999)
                _.set(query, ['where', 'first_name', 'like'], searchQuery)
                _.set(query, ['where', 'or', 'last_name', 'like'], searchQuery)

                const namesData = apiCustomers.get({query})
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
            },
            fetchEmails(searchKeyword, query = {}) {
                this.customerEmailLoading = true

                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, 'per_page', 99999)
                _.set(query, ['where', 'email', 'like'], searchQuery)

                const namesData = apiCustomers.get({query})
                return Promise.resolve(namesData)
                    .then(response => {
                        let values = []
                        const searchKeywordUpper = searchKeyword.toLowerCase()
                        const dataNotStructured = response.body.data

                        for (let item of dataNotStructured) {
                            if (item.email && _.includes(item.email.toLowerCase(), searchKeywordUpper)) {
                                if (!_.includes(values, { 'name': item.email })) {
                                    values.push({name: item.email})
                                }
                            }
                        }

                        this.customerEmailLoading = false
                        this.customerEmails = values
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            }
        }
    }
</script>