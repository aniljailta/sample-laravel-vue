<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import api from 'src/api/building-packages'

    export default {
        name: 'building-package-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return api
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Building Package # ' + this.item.id + ' ' + this.item.name
        }
    }
</script>