<template>

    <div class="col-md-12" v-if="options">
        <p><strong>Filtering all available:</strong></p>
        <dl class="dl-horizontal">
            <div v-for="(option, field_id) in options">
                <dt>{{ option.label }}</dt>
                <dd>{{ option.value }}</dd>
            </div>
        </dl>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        components: {},
        props: {
            search: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            options() {
                if (_.size(this.search) > 0) {
                    return this.search
                } else {
                    return null
                }
            }
        },
        methods: {
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>