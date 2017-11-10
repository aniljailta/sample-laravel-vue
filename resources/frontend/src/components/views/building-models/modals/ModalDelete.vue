<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import api from 'src/api/building-models'

    export default {
        name: 'building-model-modal-delete',
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
            if (this.item && this.item.id) this.modalTitle = 'Delete Building Model # ' + this.item.id + ' ' + this.item.name
        }
    }
</script>