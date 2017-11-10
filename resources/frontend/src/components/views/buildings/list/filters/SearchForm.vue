<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-text v-if="search.id === 'sort_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'plant_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'manufacture_year'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'total_price'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'order_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'sale_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_sold'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                      dataType="array"
                                      v-bind:title="'name'"
                                      v-bind:datas="buildingStatuses"
                                      v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'model_id'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="buildingModels"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'building_package_id'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="buildingPackages"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-async-text v-if="search.id === 'customer'"
                                  :is-loading="customerNamesLoading"
                                  :autocomplete-values="customerNames"
                                  @fetch-autocomplete="fetchCustomers"
                                  v-bind:item="search">
                </form-search-async-text>
                <form-search-select v-if="search.id === 'location_category'"
                                    v-bind:label="'name'"
                                    v-bind:datas="locationCategories"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-async-text v-if="search.id === 'location'"
                                        :is-loading="locationNamesLoading"
                                        :autocomplete-values="locationNames"
                                        :block-width="'320px'"
                                        @fetch-autocomplete="fetchLocations"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-radio v-if="search.id === 'serial_numbers'"
                                      v-bind:item="search"
                                      v-bind:datas="serialNumbers">
                </form-search-radio>
                <form-search-radio v-if="search.id === 'used'"
                                   v-bind:item="search"
                                   v-bind:datas="usedBuildings">
                </form-search-radio>
                <form-search-radio v-if="search.id === 'sold_status'"
                                   v-bind:item="search"
                                   v-bind:datas="soldStatuses">
                </form-search-radio>
                <form-search-select v-if="search.id === 'style_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="styles"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'width'"
                                    :label="'width'"
                                    :trackBy="'width'"
                                    v-bind:title="'name'"
                                    v-bind:datas="width"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'length'"
                                    :label="'length'"
                                    :trackBy="'length'"
                                    v-bind:title="'name'"
                                    v-bind:datas="length"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'payment_type'"
                                    v-bind:datas="paymentTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'roof_color'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="colors"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'trim_color'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="colors"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'siding_color'"
                                    dataType="array"
                                    v-bind:title="'name'"
                                    v-bind:datas="colors"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'amount_received'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'invoice_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'sales_tax'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'dealer'"
                                    :label="'businessName'"
                                    v-bind:title="'businessName'"
                                    v-bind:datas="dealers"
                                    v-bind:item="search">
                </form-search-select>
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
    import FormSearchRadio from 'src/components/views/_base/ListItems/search-forms/RadioInput.vue'
    import FormSearchAsyncText from 'src/components/views/_base/ListItems/search-forms/AsyncInput.vue'

    import apiBuildings from 'src/api/buildings'
    import apiBuildingStatuses from 'src/api/building-statuses'
    import apiBuildingModels from 'src/api/building-models'
    import apiBuildingPackages from 'src/api/building-packages'
    import apiLocations from 'src/api/locations'
    import apiStyle from 'src/api/styles'
    import apiOrders from 'src/api/orders'
    import apiDealers from 'src/api/dealers'
    import apiColors from 'src/api/colors'

    import mixinFetchCustomers from 'src/components/mixins/fetches/orders/customers'
    import mixinFetchLocations from 'src/components/mixins/fetches/locations'

    export default {
        extends: baseSearchForm,
        mixins: [
            mixinFetchCustomers,
            mixinFetchLocations
        ],
        data() {
            return {
                buildingStatuses: [],
                buildingModels: [],
                buildingPackages: [],
                serialNumbers: [
                    { id: 'all', title: 'All' },
                    { id: 'only_with_sn', title: 'Buildings with serial numbers' },
                    { id: 'only_without_sn', title: 'Buildings without serial numbers' }
                ],
                usedBuildings: [
                    { id: 'all', title: 'All' },
                    { id: 'used', title: 'Used Buildings' },
                    { id: 'new', title: 'New Buildings' }
                ],
                soldStatuses: [
                    { id: 'all', title: 'All' },
                    { id: 'sold', title: 'Sold Buildings' },
                    { id: 'not_sold', title: 'Not Sold Buildings' }
                ],
                locationCategories: {},
                styles: [],
                width: [],
                length: [],
                paymentTypes: {},
                colors: {},
                dealers: [],
                serialNumbersValues: []
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchText,
            FormSearchRadio,
            FormSearchAsyncText
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiBuildingStatuses.get({
                        query: {
                            per_page: 99999,
                            where: {
                                type: 'build'
                            },
                            order_by: ['priority']
                        }
                    }),
                    apiBuildingModels.get({
                        query: {
                            per_page: 99999,
                            where: {
                                isActive: 'yes'
                            },
                            order_by: ['style_id asc', 'width asc', 'length asc']
                        }
                    }),
                    apiStyle.get({
                        query: {
                            per_page: 99999,
                            where: {
                                is_active: 'yes'
                            },
                            order_by: ['name asc']
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            order_by: ['width asc'],
                            group_by: ['width']
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            order_by: ['length asc'],
                            group_by: ['length']
                        }
                    }),
                    apiOrders.paymentTypes(),
                    apiDealers.get({
                        params: {
                            fields: ['id', 'business_name'],
                            per_page: 9999
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            order_by: ['serial_number asc']
                        }
                    }),
                    apiBuildingPackages.get({
                        query: {
                            per_page: 99999,
                            where: {
                                isActive: 'yes'
                            },
                            order_by: ['id']
                        }
                    }),
                    apiColors.get({
                        query: {
                            per_page: 99999,
                            where: {
                                isActive: 'yes'
                            },
                            order_by: ['id']
                        }
                    }),
                    apiLocations.categories({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.buildingStatuses = response[0].data.data
                        this.buildingModels = response[1].data.data
                        this.styles = response[2].data.data
                        this.width = response[3].data.data
                        this.length = response[4].data.data
                        this.paymentTypes = response[5].data
                        this.dealers = response[6].data.data
                        this.serialNumbersValues = response[7].data.data
                        this.buildingPackages = response[8].data.data
                        this.colors = response[9].data.data
                        this.locationCategories = response[10].data

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