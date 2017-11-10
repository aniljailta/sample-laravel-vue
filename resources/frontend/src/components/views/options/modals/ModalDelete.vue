<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiOptions from 'src/api/options'

    export default {
        name: 'option-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiOptions
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Option # ' + this.item.id
        }
    }
</script>