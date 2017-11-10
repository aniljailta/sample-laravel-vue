<script type="text/babel">
    // base
    import ListItemsExtended from 'src/components/views/_base/ListItems/ListItemsExtended.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import Modals from './Modals.vue'
    import types from './types'
    import queries from './types/queries'

    import apiCustomers from 'src/api/customers'

    export default {
        extends: ListItemsExtended,
        components: {
            List,
            Filters,
            Modals
        },
        data() {
            return {
                entity: 'Customer',
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                return apiCustomers.get({ query })
            },
            queries() {
                return queries
            },
            closeModalAttachments() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>