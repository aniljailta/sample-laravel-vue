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
                if (status === 'draft' || status === 'pending' || status === 'archived') {
                    return 'label-default'
                }
                if (status === 'published') {
                    return 'label-success'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>