<script type="text/babel">
    import baseModalSave from 'src/components/views/_base/Modal/ModalSave'
    import FormItem from '../form/FormPublish.vue'

    export default {
        name: 'price-group-modal-publish',
        extends: baseModalSave,
        data() {
            return {
                modalClass: 'col-md-6 col-sm-10 col-xs-11',
                modalTitle: (this.item.name).toLowerCase().replace(/\b[a-z]/g, function(priceGroupName) {
                    return priceGroupName.toUpperCase()
                }) + ' - ' + (this.item.category).toLowerCase().replace(/\b[a-z]/g, function(category) {
                    return category.toUpperCase()
                }),
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