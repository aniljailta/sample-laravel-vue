<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormItem.vue'

    export default {
        name: 'payment-modal-create',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-8 col-sm-10 col-xs-11',
                modalTitle: 'Create Payment',
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
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>