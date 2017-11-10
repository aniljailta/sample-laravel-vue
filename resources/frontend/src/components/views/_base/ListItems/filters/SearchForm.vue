<script type="text/babel">
    import DataProcessMixin from 'src/mixins/vue-data-process'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {}
        },
        components: {},
        props: {
            searches: {
                default() { return [] }
            }
        },
        created() {
            this.$on('sync-searches', this.syncSearches)
            this.$on('fetch-data', this.fetchData)

            this.$on('update-dimension', this.$parent.updateDimension)
            this.$on('update-total', this.$parent.updateTotal)
            this.$on('update-search', this.$parent.updateSearch)

            this.$emit('fetch-data')
        },
        computed: {
            currentSearches() {
                return _.filter(this.searches, { 'checked': true })
            }
        },
        methods: {
            syncSearches() {},
            fetchData() {}
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>