<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'is_active'"
                                      v-bind:title="'title'"
                                      v-bind:datas="activeFlags"
                                      v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'option_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="options"
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

    import apiOptions from 'src/api/options'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                options: [],
                activeFlags: [
                    { id: 'yes', title: 'Yes' },
                    { id: 'no', title: 'No' }
                ]
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
                    apiOptions.get({
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
                        this.options = response[0].data.data
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