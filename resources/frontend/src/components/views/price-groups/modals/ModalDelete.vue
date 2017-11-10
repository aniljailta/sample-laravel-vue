<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiPriceGroups from 'src/api/price-groups'

    export default {
        name: 'price-group-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiPriceGroups
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Price Group # ' + this.item.id
        }
    }
</script>