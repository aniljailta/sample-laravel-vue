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
                if (status.id === 'draft') {
                    return 'label-default'
                }
                if (status.id === 'review_needed') {
                    return 'label-danger'
                }
                if (status.id === 'submitted') {
                    return 'label-warning'
                }
                if (status.id === 'signature_pending') {
                    return 'label-warning'
                }
                if (status.id === 'signed') {
                    return 'label-success'
                }
                if (status.id === 'sale_generated') {
                    return 'label-success'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>