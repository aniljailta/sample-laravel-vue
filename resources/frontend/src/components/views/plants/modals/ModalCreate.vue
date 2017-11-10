<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormItem.vue'

    export default {
        name: 'plant-modal-create',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-5 col-sm-9 col-xs-11',
                modalTitle: 'Create Plant',
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