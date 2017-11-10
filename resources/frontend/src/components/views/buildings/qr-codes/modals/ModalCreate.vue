<script type="text/babel">
    import BaseModalSave from 'src/components/views/_base/Modal/ModalSave.vue'
    import FormItem from '../FormItem.vue'

    export default {
        extends: BaseModalSave,
        name: 'modal-Qr-code-building-status',
        data() {
            return {
                modalClass: 'col-md-6 col-sm-9 col-xs-9',
                modalTitle: this.params.modaltitle,
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
