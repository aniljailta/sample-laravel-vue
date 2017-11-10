<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_submitted'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                    v-bind:datas="statuses"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'dealer'"
                                    :label="'businessName'"
                                    v-bind:title="'businessName'"
                                    v-bind:datas="dealers"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-buildings v-if="search.id === 'building_id'"
                                       v-bind:datas="buildings"
                                       v-bind:item="search">
                </form-search-buildings>
                <form-search-select v-if="search.id === 'order_type'"
                                    v-bind:datas="orderTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'payment_type'"
                                    v-bind:datas="paymentTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'sale_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-date v-if="search.id === 'order_date'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-async-text v-if="search.id === 'customer'"
                                        :is-loading="customerNamesLoading"
                                        :autocomplete-values="customerNames"
                                        @fetch-autocomplete="fetchCustomers"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-text v-if="search.id === 'invoice_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'dealer_commission'"
                                    :label="'dealerCommission'"
                                    :trackBy="'dealerCommission'"
                                    v-bind:title="'name'"
                                    v-bind:datas="dealerCommissions"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'retail'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'sales_tax'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'deposit'"
                                  v-bind:item="search">
                </form-search-text>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'

    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'
    import FormSearchBuildings from './search-forms/Buildings.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    import FormSearchAsyncText from 'src/components/views/_base/ListItems/search-forms/AsyncInput.vue'

    import apiSales from 'src/api/sales'
    import apiOrders from 'src/api/orders'
    import apiDealers from 'src/api/dealers'
    import apiBuildings from 'src/api/buildings'

    import mixinFetchCustomers from 'src/components/mixins/fetches/orders/customers'

    export default {
        extends: baseSearchForm,
        mixins: [
            mixinFetchCustomers
        ],
        data() {
            return {
                statuses: {},
                dealers: [],
                buildings: [],
                orderTypes: {},
                paymentTypes: {},
                dealerCommissions: []
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchBuildings,
            FormSearchText,
            FormSearchAsyncText
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiSales.statuses(),
                    apiDealers.get({
                        params: {
                            fields: ['id', 'business_name'],
                            per_page: 9999
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            fields: ['id', 'serial_number'],
                            per_page: 9999,
                            order_by: ['serial_number asc']
                        }
                    }),
                    apiOrders.paymentTypes(),
                    apiOrders.orderTypes(),
                    apiOrders.get({
                        query: {
                            per_page: 99999,
                            order_by: ['dealer_commission asc'],
                            group_by: ['dealer_commission']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.statuses = response[0].data
                        this.dealers = response[1].data.data
                        this.buildings = response[2].data.data
                        this.paymentTypes = response[3].data
                        this.orderTypes = response[4].data
                        this.dealerCommissions = response[5].data.data
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