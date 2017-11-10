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
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'

    import apiDealers from 'src/api/dealers'

    export default {
        extends: baseSearchForm,
        data() {
            return {
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
                const datas = [apiDealers.activeFlags({})]

                return Promise.all(datas)
                    .then(response => {
                        this.activeFlags = response[0].data
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