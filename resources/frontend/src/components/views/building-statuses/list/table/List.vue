<script type="text/babel">
    import baseList from 'src/components/views/_base/ListItems/list/List.vue'
    import ListItem from './ListItem.vue'
    import draggable from 'vuedraggable'
    import apiBuildingStatuses from 'src/api/building-statuses'

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
                    oldPriority: evt.moved.oldIndex,
                    newPriority: evt.moved.newIndex
                }
                this.$emit('data-process-update', {
                    running: true
                })
                return apiBuildingStatuses.updatePriorities({ data })
                    .then(data => {
                        self.refreshList()
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>