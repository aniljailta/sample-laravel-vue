<template>

    <span class="label" :class="labelClass(location)">{{ location.name }}</span>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        props: {
            location: {
                required: true,
                default() {
                    return {}
                }
            }
        },

        methods: {
            labelClass(location) {
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>