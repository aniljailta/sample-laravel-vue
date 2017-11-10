<script type="text/babel">
    export default {
        components: {},
        data() {
            return {}
        },
        computed: {
            modal() {
                return this.$parent.modal
            }
        },
        methods: {
            fetchData() {
                this.$parent.fetchData()
            },
            openModalCreate() {
                this.$emit('change-entry', {
                    item: {},
                    mode: 'create'
                })
            },
            closeModalCreate() {
                this.$emit('change-entry', null)
            },
            closeModalUpdate() {
                this.$emit('change-entry', null)
            },
            closeModalDelete() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>