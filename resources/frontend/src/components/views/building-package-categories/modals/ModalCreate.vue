<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormItem.vue'

    export default {
        name: 'building-package-category-modal-create',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-5 col-sm-7 col-xs-9',
                modalTitle: 'Create Building Package Category',
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