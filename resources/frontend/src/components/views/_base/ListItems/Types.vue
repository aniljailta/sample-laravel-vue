<template>
    <div></div>
</template>

<script type="text/babel">
    export default {
        components: {
        },
        data() {
            return {}
        },
        props: {
            process: {
                default() { return {} }
            }
        },
        methods: {
            selectType(type) {
                return this.$parent.selectType(type)
            }
        }
    }
</script>