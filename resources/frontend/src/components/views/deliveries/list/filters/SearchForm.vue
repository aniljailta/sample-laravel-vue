<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                      :label="'name'"
                                      v-bind:title="'name'"
                                      v-bind:datas="statuses"
                                      v-bind:item="search">
                </form-search-select>
                <form-search-buildings v-if="search.id === 'building_id'"
                                       v-bind:datas="buildings"
                                       v-bind:item="search">
                </form-search-buildings>
                <form-search-select v-if="search.id === 'start_location_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="locations"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'end_location_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="locations"
                                    v-bind:item="search">
                </form-search-select>

                <form-search-select v-if="search.id === 'driver_id'"
                                    :label="'fullName'"
                                    v-bind:title="'fullName'"
                                    v-bind:datas="drivers"
                                    v-bind:item="search">
                </form-search-select>

                <form-search-select v-if="search.id === 'truck_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="trucks"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'trailer_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="trailers"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'priority_id'"
                                       :label="'name'"
                                       v-bind:title="'name'"
                                       v-bind:datas="priorities"
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
    import FormSearchBuildings from './search-forms/Buildings.vue'

    import apiDeliveries from 'src/api/deliveries'
    import apiLocations from 'src/api/locations'
    import apiBuildings from 'src/api/buildings'
    import apiTrucks from 'src/api/delivery-trucks'
    import apiTrailers from 'src/api/delivery-trailers'
    import apiDrivers from 'src/api/users'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                statuses: {},
                locations: [],
                buildings: [],
                priorities: [],
                trucks: [],
                trailers: [],
                drivers: []
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchBuildings
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiDeliveries.statuses({}),
                    apiBuildings.get({
                        query: {
                            fields: ['id', 'serial_number'],
                            per_page: 999999,
                            order_by: ['id asc']
                        }
                    }),
                    apiLocations.get({
                        query: {
                            fields: ['id', 'name'],
                            per_page: 999999
                        }
                    }),
                    apiDeliveries.priorities({}),
                    apiTrucks.get({
                        query: {
                            fields: ['id', 'name'],
                            per_page: 999999
                        }
                    }),
                    apiTrailers.get({
                        query: {
                            fields: ['id', 'name'],
                            per_page: 999999
                        }
                    }),
                    apiDrivers.get({
                         query: {
                            fields: ['id', 'first_name', 'last_name'],
                            per_page: 999999
                         }
                     })

                ]

                return Promise.all(datas)
                    .then(response => {
                        this.statuses = response[0].data
                        this.buildings = response[1].data.data
                        this.locations = response[2].data.data
                        this.priorities = response[3].data
                        this.trucks = response[4].data.data
                        this.trailers = response[5].data.data
                        this.drivers = response[6].data.data

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