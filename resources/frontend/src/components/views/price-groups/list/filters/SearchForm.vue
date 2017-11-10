<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-text v-if="search.id === 'name'"
                                  v-bind:item="search">
                </form-search-text>

                <form-search-select v-if="search.id === 'category'"
                                    v-bind:datas="priceGroupCategories"
                                    v-bind:item="search">
                </form-search-select>

                <form-search-select v-if="search.id === 'status'"
                                    v-bind:datas="priceGroupStatuses"
                                    v-bind:item="search">
                </form-search-select>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'

    import apiPriceGroups from 'src/api/price-groups'
    
    export default {
        extends: baseSearchForm,
        data() {
            return {
                name: '',
                priceGroupCategories: {},
                priceGroupStatuses: {}
            }
        },
        components: {
            FormSearchText,
            FormSearchSelect
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiPriceGroups.get({
                        query: {
                            per_page: 99999,
                            order_by: ['name asc']
                        }
                    }),
                    apiPriceGroups.priceGroupCategories({
                        query: {
                            per_page: 99999,
                            order_by: ['category asc']
                        }
                    }),
                    apiPriceGroups.priceGroupStatuses({
                        query: {
                            per_page: 99999,
                            order_by: ['status asc']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.name = response[0].data.data
                        this.priceGroupCategories = response[1].data
                        this.priceGroupStatuses = response[2].data
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