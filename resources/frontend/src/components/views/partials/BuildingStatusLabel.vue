<template>

    <span class="label" :class="labelClass(status)">{{ status.name }}</span>

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
                if (status.name === 'Draft') {
                    return 'label-default'
                }
                if (status.name === 'Pending') {
                    return 'label-default'
                }
                if (status.name === 'Building Started') {
                    return 'label-warning'
                }
                if (status.name === 'QC') {
                    return 'label-warning'
                }
                if (status.name === 'Ready to Deliver') {
                    return 'label-success'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>