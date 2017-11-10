<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'is_active'"
                                      :label="'name'"
                                      v-bind:title="'name'"
                                      v-bind:datas="activeFlags"
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

    import apiStyle from 'src/api/styles'
    import apiBuildingModels from 'src/api/building-models'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                styles: [],
                activeFlags: {}
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiStyle.get({
                        query: {
                            per_page: 99999,
                            where: {
                                is_active: 'yes'
                            },
                            order_by: ['name asc']
                        }
                    }),
                    apiBuildingModels.activeFlags({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.styles = response[0].data.data
                        this.activeFlags = response[1].data
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