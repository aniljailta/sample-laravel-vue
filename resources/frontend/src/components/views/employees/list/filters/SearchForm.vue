<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-text v-if="search.id === 'first_name'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'email'"
                                  v-bind:item="search">
                </form-search-text>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    
    import apiEmployees from 'src/api/employees'
    
    export default {
        extends: baseSearchForm,
        data() {
            return {
                firstName: [],
                email: []
            }
        },
        components: {
            FormSearchText
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiEmployees.get({
                        query: {
                            per_page: 99999,
                            order_by: ['first_name asc']
                        }
                    }),
                    apiEmployees.get({
                        query: {
                            per_page: 99999,
                            order_by: ['email asc']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.firstName = response[0].data.data
                        this.email = response[1].data.data
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