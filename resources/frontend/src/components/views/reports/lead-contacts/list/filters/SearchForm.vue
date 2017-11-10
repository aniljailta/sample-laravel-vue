<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-text v-if="search.id === 'order_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'customer_id'"
                                    v-bind:datas="customers"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'initial_contact'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'order_submit'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'total_price'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-date v-if="search.id === 'date_submitted'"
                                  v-bind:item="search">
                </form-search-date>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'

    import apiLeadContacts from 'src/api/lead-contacts'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                orderId: '',
                customers: {},
                orders: {}
            }
        },
        components: {
            FormSearchText,
            FormSearchDate,
            FormSearchSelect
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiLeadContacts.leadContacts({
                        query: {
                            fields: ['order_id'],
                            per_page: 9999
                        }
                    }),
                    apiLeadContacts.customers({
                        query: {
                            per_page: 99999,
                            order_by: ['customer_id asc']
                        }
                    }),
                    apiLeadContacts.orders({
                        query: {
                            per_page: 99999,
                            order_by: ['order_id asc']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.orderId = response[0].data.data
                        this.customers = response[1].data
                        this.orders = response[2].data
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