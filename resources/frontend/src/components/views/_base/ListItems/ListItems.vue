<script type="text/babel">
    /*
     * Basic order flow is:
     * -> parse route path & apply route params to filters: applyRouteToFilters()
     * -> parse filters: fetchData()
     * -> call api query: fetchData()
     *
     * Basic 'Search' button flow is:
     * -> parse filters and apply filter params to route: applyFiltersToRoute()
     * -> see [Basic flow]
     *
     */
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import Types from './Types.vue'
    import _helpers from 'src/components/views/_base/ListItems/_helpers'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                initial: true,
                types: {},
                type: {},
                dependencies: [],
                perPageCount: 'System Default'
            }
        },
        components: {
            DataProcess,
            Types
        },
        props: {
            title: {
                type: String,
                default: ''
            }
        },
        created() {
            this.$on('fetchData', this.fetchData)
            this.$on('receiveList', this.receiveList)
            this.$on('apply-route-to-filters', this.applyRouteToFilters)
            this.$on('apply-filters-to-route', this.applyFiltersToRoute)

            this.$on('update-dimension', this.updateDimension)
            this.$on('update-total', this.updateTotal)
            this.$on('update-search', this.updateSearch)
            this.$on('update-extra', this.updateExtra)

            this.$on('update-per-page', this.updatePerPage)

            this.$watch('$route', () => {
                this.$emit('receiveList')
            })
        },
        computed: {
            dataIsReady() {
                return !((this.dataProcess.running === true || this.dataProcess.error) && this.dataProcess.type === 'data')
            }
        },
        methods: {
            receiveList() {
                // check if it is first time route request (and route query is empty, if not - it means,
                // than user put url directly?)
                // if so - select schema by default and try to fetch data again
                if (_.isEmpty(this.$route.query)) {
                    if (this.initial === true) {
                        this.initial = false
                        this.selectType('def', false)
                    } else {
                        this.selectType('def', false)
                        this.$emit('fetchData')
                    }
                    return
                }

                return new Promise((resolve) => {
                    this.$emit('apply-route-to-filters')
                    resolve()
                }).then(() => {
                    this.$emit('fetchData')
                })
            },
            selectType(type, fetch = true) {
                this.type = _.cloneDeep(this.types[type])
                // start fetching data once after set type
                if (fetch) this.applyFiltersToRoute()
            },
            search() {
                this.$emit('update-extra', {
                    page: null,
                    orderBy: null
                })
                this.$emit('apply-filters-to-route')
            },
            applyRouteToFilters() {
                // parse dimensions,totals,searches settings based on ROUTE path
                let { newDimensions, newTotals, newSearches, newExtra } = _helpers.parseRoute({
                    $router: this.$router,
                    dimensions: this.type.dimensions,
                    totals: this.type.totals,
                    searches: this.type.searches,
                    extra: this.type.extra
                })
                this.type.dimensions = newDimensions
                this.type.totals = newTotals
                this.type.searches = newSearches
                this.type.extra = newExtra
            },
            applyFiltersToRoute() {
                let filterParams = _helpers.parseFilters({
                    dimensions: this.type.dimensions,
                    totals: this.type.totals,
                    searches: this.type.searches,
                    extra: this.type.extra,
                    queries: this.queries() // dynamic (difficult) queries
                })

                let currentPath = '?' + _helpers.qs.stringify(this.$route.query)
                let newPath = '?' + _helpers.qs.stringify(_.assign({}, {
                    dimensions: filterParams.dimensionsParams.routeAlias,
                    totals: filterParams.totalsParams.routeAlias,
                    searches: filterParams.searchesParams.routeAlias,
                    ...filterParams.extraParams.routeAlias
                }))

                if (_.isEqual(currentPath, newPath)) {
                    this.$emit('fetchData')
                } else {
                    this.$router.push(newPath)
                }
            },
            // parse FILTER and CALL API based on filters
            fetchData() {
                let filterParams = _helpers.parseFilters({
                    dimensions: this.type.dimensions,
                    totals: this.type.totals,
                    searches: this.type.searches,
                    extra: this.type.extra,
                    queries: this.queries(), // dynamic (difficult) queries
                    perPageCount: this.perPageCount
                })
                let query = filterParams.apiQuery

                this.$refs.table.$emit('data-process-update', {
                    type: 'list',
                    running: true,
                    error: null,
                    success: null
                })

                return this.apiGet(query).then(response => {
                    this.$refs.table.dimensions = _.cloneDeep(this.type.dimensions)
                    this.$refs.table.totals = _.cloneDeep(this.type.totals)
                    this.$refs.table.setResponse(response)
                    // this.$refs.table.$emit('data-ready')
                }).catch(response => {
                    this.$refs.table.$emit('data-failed', response)
                })
            },
            updateDimension(item) {
                let index = _.findIndex(this.type.dimensions, {id: item.id})
                this.type.dimensions.splice(index, 1, item)
            },
            updateTotal(item) {
                let index = _.findIndex(this.type.totals, {id: item.id})
                this.type.totals.splice(index, 1, item)
            },
            updateSearch(item) {
                let index = _.findIndex(this.type.searches, {id: item.id})
                this.type.searches.splice(index, 1, item)
            },
            updateExtra(extra) {
                this.type.extra = _.assign({}, this.type.extra, extra)
            },
            queries() { return {} },
            depReady(dep) {
                this.dependencies.push(dep)

                let required = []
                if (!_.isEmpty(this.type)) required.push('filters')

                let allReady = _.every(required, el => _.includes(this.dependencies, el), this)
                if (allReady) {
                    this.$emit('data-ready')
                }
            },
            dataAllReady() {
                this.$emit('receiveList')
            },
            updatePerPage(value) {
                this.perPageCount = value
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/pages/lists.scss"></style>