<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_updated'"
                                  v-bind:item="search">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_submitted'"
                                  v-bind:item="search">
                </form-search-date>
                <form-search-date v-if="search.id === 'order_date'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                    v-bind:title="'title'"
                                    v-bind:datas="orderStatuses"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'order_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'model_id'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="buildingModels"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'retail'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'sales_tax'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'deposit_received'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'order_type'"
                                    :label="'title'"
                                    v-bind:title="'name'"
                                    v-bind:datas="orderTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'dealer'"
                                    :label="'businessName'"
                                    v-bind:title="'businessName'"
                                    v-bind:datas="dealers"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-async-text v-if="search.id === 'sales_person'"
                                        :is-loading="salesPersonNamesLoading"
                                        :autocomplete-values="salesPersonNames"
                                        @fetch-autocomplete="fetchSalesPerson"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-async-text v-if="search.id === 'customer'"
                                        :is-loading="customerNamesLoading"
                                        :autocomplete-values="customerNames"
                                        @fetch-autocomplete="fetchCustomers"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-select v-if="search.id === 'serial_numbers_values'"
                                    :label="'serialNumber'"
                                    :trackBy="'serialNumber'"
                                    v-bind:title="'name'"
                                    v-bind:datas="serialNumbersValues"
                                    v-bind:item="search">
                </form-search-select>
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

    import apiOrders from 'src/api/orders'
    import apiBuildingModels from 'src/api/building-models'
    import apiDealers from 'src/api/dealers'
    import apiBuildings from 'src/api/buildings'

    import mixinFetchCustomers from 'src/components/mixins/fetches/orders/customers'
    import mixinFetchSalesPersons from 'src/components/mixins/fetches/orders/sales-persons'

    export default {
        extends: baseSearchForm,
        mixins: [
            mixinFetchCustomers,
            mixinFetchSalesPersons
        ],
        data() {
            return {
                orderStatuses: {},
                buildingModels: [],
                orderTypes: [],
                serialNumbersValues: []
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
                const datas = [
                    apiOrders.statuses(),
                    apiBuildingModels.get({
                        query: {
                            per_page: 99999,
                            where: {
                                isActive: 'yes'
                            },
                            order_by: ['style_id asc', 'width asc', 'length asc']
                        }
                    }),
                    apiOrders.orderTypes(),
                    apiDealers.get({
                        params: {
                            fields: ['id', 'business_name'],
                            per_page: 9999
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            fields: ['id', 'serial_number'],
                            order_by: ['serial_number asc']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.orderStatuses = response[0].data
                        this.buildingModels = response[1].data.data
                        this.orderTypes = response[2].data
                        this.dealers = response[3].data.data
                        this.serialNumbersValues = response[4].data.data
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            }
        }
    }
</script>