<script type="text/babel">
    import baseListItemsReport from 'src/components/views/_base/ListItems/ListItemsReport.vue'
    import List from './table/List.vue'
    import Filters from './Filters.vue'
    import types from './types'
    import queries from './types/queries'

    import apiLeadContacts from 'src/api/lead-contacts'
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
                apiPath: 'lead-contacts'
            }
        },
        methods: {
            apiGet(query) {
                let currentQuery = this.$url(apiLeadContacts.actions.leadContacts.url, snakeCaseObjectKeys(query))
                this.exportUrl = this.getQueryParamsOnly(currentQuery)

                return apiLeadContacts.leadContacts({ query })
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