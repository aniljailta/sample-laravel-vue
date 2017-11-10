<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormItem.vue'

    export default {
        name: 'option-category-modal-update',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-6 col-sm-7 col-xs-11',
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
            if (this.item && this.item.id) this.modalTitle = 'Update Option Category # ' + this.item.id
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>