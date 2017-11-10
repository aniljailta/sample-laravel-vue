<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import api from 'src/api/plants'

    export default {
        name: 'plant-modal-delete',
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
            if (this.item && this.item.plantId) this.modalTitle = 'Delete Plant # ' + this.item.plantId + ' ' + this.item.name
        }
    }
</script>