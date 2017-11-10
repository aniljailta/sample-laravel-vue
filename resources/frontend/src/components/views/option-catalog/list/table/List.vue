<script type="text/babel">
    import baseList from '../List.vue'
    import ListItem from './ListItem.vue'
    import draggable from 'vuedraggable'

    export default {
        extends: baseList,
        data() {
            return {}
        },
        components: {
            ListItem,
            draggable
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>