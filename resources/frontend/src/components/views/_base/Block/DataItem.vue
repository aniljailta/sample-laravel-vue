<script type="text/babel">
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {}
        },
        components: {
            DataProcess
        },
        props: {
            item: {
                required: false,
                default() {
                    return {}
                }
            },
            params: {
                required: false,
                default() {
                    return {}
                }
            }
        },
        created() {
            this.initData()
        },
        computed: {
            dataIsReady() {
                return !((this.dataProcess.running === true || this.dataProcess.error) && this.dataProcess.type === 'data')
            }
        },
        methods: {
            initData() {}
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .form-group .row {
        margin-bottom: 0.5em;
    }
</style>