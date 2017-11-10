<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiColors from 'src/api/colors'

    export default {
        name: 'color-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiColors
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Color # ' + this.item.id
        }
    }
</script>