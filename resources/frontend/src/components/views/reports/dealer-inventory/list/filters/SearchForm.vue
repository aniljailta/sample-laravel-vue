<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search">
                </form-search-date>
                <form-search-select v-if="search.id === 'dealer'"
                                     :label="'name'"
                                     v-bind:title="'name'"
                                     v-bind:datas="dealers"
                                     v-bind:item="search">
                </form-search-select>
                <form-search-date v-if="search.id === 'location_date'"
                                  v-bind:item="search">
                </form-search-date>
                <form-search-select v-if="search.id === 'serial_numbers_values'"
                                    :label="'serialNumber'"
                                    :trackBy="'serialNumber'"
                                    v-bind:title="'name'"
                                    v-bind:datas="serialNumbersValues"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'retail'"
                                  v-bind:item="search">
                </form-search-text>
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
                <form-search-select v-if="search.id === 'style_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="styles"
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

    import apiBuildings from 'src/api/buildings'
    import apiBuildingModels from 'src/api/building-models'
    import apiBuildingPackages from 'src/api/building-packages'
    import apiLocations from 'src/api/locations'
    import apiStyle from 'src/api/styles'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                dealers: [],
                serialNumbersValues: [],
                buildingModels: [],
                buildingPackages: [],
                styles: []
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
                    apiLocations.get({
                        query: {
                            fields: ['id', 'name'],
                            per_page: 9999,
                            where: {
                                category: 'dealer'
                            }
                        }
                    }),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            fields: ['id', 'serial_number'],
                            order_by: ['serial_number asc']
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
                    apiBuildingPackages.get({
                        query: {
                            per_page: 99999
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
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.dealers = response[0].data.data
                        this.serialNumbersValues = response[1].data.data
                        this.buildingModels = response[2].data.data
                        this.buildingPackages = response[3].data.data
                        this.styles = response[4].data.data
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