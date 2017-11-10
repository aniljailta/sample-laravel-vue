<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiTrucks from 'src/api/delivery-trucks'

    export default {
        name: 'truck-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiTrucks
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Truck # ' + this.item.id
        }
    }
</script>