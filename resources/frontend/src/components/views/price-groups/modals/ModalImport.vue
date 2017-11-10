<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormImport.vue'

    export default {
        name: 'price-group-modal-import',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-9 col-sm-10 col-xs-11',
                modalTitle: 'Import Price Group',
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