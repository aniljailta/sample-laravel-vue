<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiBuildingLocations from 'src/api/building-locations'

    export default {
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiBuildingLocations.delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete location history'
        }
    }
</script>