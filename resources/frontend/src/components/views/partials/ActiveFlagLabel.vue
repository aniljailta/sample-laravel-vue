<template>

    <span class="label" :class="labelClass(flag)">{{ flag.name }}</span>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        props: {
            flag: {
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
            labelClass(flag) {
                if (flag.id === 'no') {
                    return 'label-danger'
                }
                if (flag.id === 'update_required') {
                    return 'label-warning'
                }
                if (flag.id === 'yes') {
                    return 'label-success'
                }
                return 'label-default'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>