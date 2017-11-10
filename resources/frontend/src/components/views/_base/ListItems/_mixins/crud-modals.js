export default {
    data() {
        return {
            modal: null
        }
    },
    created() {
        this.$on('change-entry', this.changeEntry)
    },
    methods: {
        changeEntry(modal) {
            this.modal = modal
        },
        // TODO: deprecate open/close methods
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
