<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiCustomers from 'src/api/customers'

    export default {
        name: 'customer-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiCustomers
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Customer # ' + this.item.id
        }
    }
</script>