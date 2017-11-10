<script type="text/babel">
    import baseModalDelete from 'src/components/views/_base/Modal/ModalDelete'
    import apiEmployees from 'src/api/employees'

    export default {
        name: 'employee-modal-delete',
        extends: baseModalDelete,
        methods: {
            removeItem({ item }) {
                return apiEmployees
                    .delete({ item })
                    .then(response => response.data)
                    .catch(msg => Promise.reject(msg))
            }
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Delete Employee # ' + this.item.id
        }
    }
</script>