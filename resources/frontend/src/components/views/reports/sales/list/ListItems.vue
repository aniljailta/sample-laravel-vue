<script type="text/babel">
    import baseListItemsReport from 'src/components/views/_base/ListItems/ListItemsReport.vue'
    import List from './table/List.vue'
    import Types from './Types.vue'
    import Filters from './Filters.vue'
    import types from './types/index.js'
    import queries from './types/queries'

    import apiSales from 'src/api/sales'
    import snakeCaseObjectKeys from 'src/helpers/snake-case-converter'

    export default {
        extends: baseListItemsReport,
        components: {
            Types,
            List,
            Filters
        },
        data() {
            return {
                types: types,
                type: _.cloneDeep(types.def),
                exportUrl: '',
                apiPath: 'sales'
            }
        },
        methods: {
            apiGet(query) {
                let currentQuery = this.$url(apiSales.actions.get.url, snakeCaseObjectKeys(query))
                this.exportUrl = this.getQueryParamsOnly(currentQuery)

                return apiSales.get({ query })
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