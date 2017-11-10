<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import api from 'src/api/building-statuses'

    export default {
        name: 'building-status-modal-delete',
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
            if (this.item && this.item.id) this.modalTitle = 'Delete Building Status # ' + this.item.id + ' ' + this.item.name
        }
    }
</script>