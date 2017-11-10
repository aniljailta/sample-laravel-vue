<script type="text/babel">
    import baseList from 'src/components/views/_base/ListItems/list/List.vue'
    import ListItem from './ListItem.vue'
    import draggable from 'vuedraggable'
    import apiSyleCatalog from 'src/api/style-catalog'

    export default {
        extends: baseList,
        data() {
            return {
                drag: true
            }
        },
        components: {
            ListItem,
            draggable
        },
        methods: {
            onChange: function(evt) {
                let self = this
                let data = {
                    oldSortId: evt.moved.oldIndex + 1,
                    newSortId: evt.moved.newIndex + 1
                }
                this.$emit('data-process-update', {
                    running: true
                })
                return apiSyleCatalog.updateSortId({ data })
                    .then(data => {
                        self.refreshList()
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>