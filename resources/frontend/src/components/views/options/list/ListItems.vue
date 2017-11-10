<script type="text/babel">
    // base
    import baseListItemsCrud from './ListItemsCrud.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import types from './types'
    import queries from './types/queries'
    import apiOptions from 'src/api/options'
    // modals
    import ModalCreate from '../modals/ModalCreate.vue'
    import ModalOptionCatalog from '../modals/ModalOptionCatalog.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'

    export default {
        extends: baseListItemsCrud,
        components: {
            List,
            Filters,
            ModalCreate,
            ModalUpdate,
            ModalDelete,
            ModalOptionCatalog
        },
        data() {
            return {
                entity: 'Option',
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                query['sortByCategory'] = true
                return apiOptions.get({ query })
            },
            queries() {
                return queries
            }
        }
    }
</script>