<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiBuildings from 'src/api/buildings'

    export default {
        name: 'building-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiBuildings
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Building # ' + this.item.id
        }
    }
</script>