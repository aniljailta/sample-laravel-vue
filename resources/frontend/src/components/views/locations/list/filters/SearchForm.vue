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
                <form-search-select v-if="search.id === 'category'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="categories"
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

    import apiLocations from 'src/api/locations'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                categories: [],
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
                    apiLocations.categories({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.categories = response[0].data
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