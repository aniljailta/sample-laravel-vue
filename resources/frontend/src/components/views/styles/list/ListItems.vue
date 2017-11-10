<script type="text/babel">
    // base
    import listItemsCrud from './ListItemsCrud.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import types from './types'
    import queries from './types/queries'
    import api from 'src/api/styles'
    // modals
    import ModalStyleCatalog from '../modals/ModalStyleCatalog.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'

    export default {
        extends: listItemsCrud,
        components: {
            List,
            Filters,
            ModalStyleCatalog,
            ModalUpdate,
            ModalDelete
        },
        data() {
            return {
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                return api.get({ query })
            },
            queries() {
                return queries
            }
        }
    }
</script>