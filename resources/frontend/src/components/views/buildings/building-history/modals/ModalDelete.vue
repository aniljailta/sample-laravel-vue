<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiBuildingHistories from 'src/api/building-histories'

    export default {
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiBuildingHistories.delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete status history'
        }
    }
</script>