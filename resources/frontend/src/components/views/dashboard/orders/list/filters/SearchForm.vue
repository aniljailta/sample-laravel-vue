<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-select v-if="search.id === 'status_id'"
                                    v-bind:title="'title'"
                                    v-bind:datas="orderStatuses"
                                    v-bind:item="search">
                </form-search-select>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'

    import apiOrders from 'src/api/orders'

    import mixinFetchCustomers from 'src/components/mixins/fetches/orders/customers'
    import mixinFetchSalesPersons from 'src/components/mixins/fetches/orders/sales-persons'

    export default {
        extends: baseSearchForm,
        mixins: [
            mixinFetchCustomers,
            mixinFetchSalesPersons
        ],
        data() {
            return {
                orderStatuses: {}
            }
        },
        components: {
            FormSearchSelect
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiOrders.statuses()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.orderStatuses = response[0].data
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