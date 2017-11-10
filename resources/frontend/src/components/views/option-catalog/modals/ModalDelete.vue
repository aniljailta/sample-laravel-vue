<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiOptionCatalog from 'src/api/option-catalog'

    export default {
        name: 'option-catalog-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiOptionCatalog
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