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
                type: Object,
                default() {
                    return {
                        id: null,
                        name: null
                    }
                },
                required: true
            }
        },

        methods: {
            labelClass(status) {
                if (status.id === 'pending') {
                    return 'label-default'
                }
                if (status.id === 'scheduled') {
                    return 'label-warning'
                }
                if (status.id === 'completed') {
                    return 'label-success'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>