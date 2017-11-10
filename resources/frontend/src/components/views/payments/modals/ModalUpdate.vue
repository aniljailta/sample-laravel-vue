<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormItem.vue'

    export default {
        name: 'payment-modal-update',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-8 col-sm-10 col-xs-11',
                modalTitle: '',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                }
            }
        },
        components: {
            FormItem
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Update Payment # ' + this.item.id
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>