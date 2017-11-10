<template>

    <span class="label" :class="labelClass(status)">{{ status.title }}</span>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        props: {
            status: {
                type: Object,
                default() {
                    return {
                        id: null
                    }
                },
                required: true
            }
        },

        methods: {
            labelClass(status) {
                let className = 'label-default'
                if (status.id === 'closed') {
                    className = 'label-default'
                }
                if (status.id === 'cancelled') {
                    className = 'label-default'
                }
                if (status.id === 'invoiced') {
                    className = 'label-danger'
                }
                if (status.id === 'open') {
                    className = 'label-success'
                }
                return className
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>