<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiLocations from 'src/api/locations'

    export default {
        name: 'location-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiLocations
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Location # ' + this.item.id
        }
    }
</script>