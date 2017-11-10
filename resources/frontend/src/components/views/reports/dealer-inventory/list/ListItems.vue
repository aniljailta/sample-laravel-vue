<script type="text/babel">
    import baseListItemsReport from 'src/components/views/_base/ListItems/ListItemsReport.vue'
    import List from './table/List.vue'
    import Filters from './Filters.vue'
    import types from './types'
    import queries from './types/queries'

    import apiBuildings from 'src/api/buildings'
    import snakeCaseObjectKeys from 'src/helpers/snake-case-converter'

    export default {
        extends: baseListItemsReport,
        components: {
            List,
            Filters
        },
        data() {
            return {
                types: types,
                type: _.cloneDeep(types.def),
                exportUrl: '',
                apiPath: 'buildings'
            }
        },
        methods: {
            apiGet(query) {
                let currentQuery = this.$url(apiBuildings.actions.inventory.url, snakeCaseObjectKeys(query))
                this.exportUrl = this.getQueryParamsOnly(currentQuery)

                return apiBuildings.inventory({ query })
            },
            queries() {
                return queries
            },
            getQueryParamsOnly(currentQuery) {
                return currentQuery.substring(currentQuery.indexOf('?') + 1)
            }
        }
    }
</script>