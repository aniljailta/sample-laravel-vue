<script type="text/babel">
    import baseListItem from './ListItem.vue'

    export default {
        extends: baseListItem,
        data() {
            return {}
        },
        components: {},
        methods: {
            openUpdateModal() {
                this.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'edit'
                })
            },
            openDeleteModal() {
                this.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'delete'
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>