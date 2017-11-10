<script type="text/babel">
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                initial: true,
                modalCreate: false,
                searchForm: {
                    show: false,
                    filtered: false
                }
            }
        },
        components: {
            DataProcess
        },
        props: {
            title: {
                type: String,
                default: ''
            }
        },
        created() {
            this.fetchData()
        },
        computed: {
            dataIsReady() {
                return !((this.dataProcess.running === true || this.dataProcess.error) && this.dataProcess.type === 'data')
            }
        },
        methods: {
            openModalCreate() {
                this.select({
                    item: {},
                    mode: 'create'
                })
            },
            closeModalCreate() {
                this.select(null)
            },
            openModalImport() {
                this.select({
                    item: {},
                    mode: 'import'
                })
            },
            closeModalImport() {
                this.select(null)
            },
            openModalPublish() {
                this.select({
                    item: {},
                    mode: 'publish'
                })
            },
            closeModalPublish() {
                this.select(null)
            },
            closeModalUpdate() {
                this.select(null)
            },
            closeModalDelete() {
                this.select(null)
            }
        }
    }
</script>

<style type="text/css">

</style>