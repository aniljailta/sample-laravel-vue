<script type="text/babel">
    // base
    import baseListItemsCrud from 'src/components/views/_base/ListItems/ListItemsCrud.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import types from './types'
    import queries from './types/queries'
    import api from 'src/api/dealers'
    // modals
    import ModalCreate from '../modals/ModalCreate.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'

    export default {
        extends: baseListItemsCrud,
        components: {
            List,
            Filters,
            ModalCreate,
            ModalUpdate,
            ModalDelete
        },
        data() {
            return {
                entity: 'Dealer',
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