<script type="text/babel">
    import BaseModalSave from 'src/components/views/_base/Modal/ModalSave.vue'
    import FormItem from '../FormItem.vue'

    export default {
        extends: BaseModalSave,
        name: 'modal-building-change-location',
        data() {
            return {
                modalClass: 'col-md-3 col-sm-5 col-xs-9'
            }
        },
        components: {
            FormItem
        },
        created() {
            if (this.item && this.item.id) this.modalTitle = 'Change building location'
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>