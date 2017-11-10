<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        components: {},
        props: {
            item: {
                type: Object,
                required: true
            },
            index: {
                required: true
            },
            dimensions: {
                required: true,
                default() { return {} }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>