<template>

    <span class="label" :class="labelClass(status)">{{ status }}</span>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        props: {
            status: {
                required: true,
                default() {
                    return {}
                }
            }
        },

        methods: {
            labelClass(status) {
                if (status === 'pending') {
                    return 'label-default'
                }
                if (status === 'complete') {
                    return 'label-success'
                }
                if (status === 'cancelled') {
                    return 'label-danger'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>