<script type="text/babel">
    // base
    import ListItemsExtended from 'src/components/views/_base/ListItems/ListItemsExtended.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import Modals from './Modals.vue'
    import types from './types'
    import queries from './types/queries'
    import apiBuildings from 'src/api/buildings'

    import Presets from './Presets.vue'

    export default {
        extends: ListItemsExtended,
        components: {
            List,
            Filters,
            Modals,
            Types: Presets
        },
        data() {
            return {
                entity: 'Building',
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                return apiBuildings.get({ query })
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