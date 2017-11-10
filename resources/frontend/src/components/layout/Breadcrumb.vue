<template>
    <li v-if="value">
        <router-link :to="{name: route.name}">
            {{ value }}
        </router-link>
    </li>
</template>

<script type="text/babel">
    export default {
        props: ['route'],
        computed: {
            value() {
                if (_.isString(this.route.meta.bcTitle)) {
                    return this.route.meta.bcTitle
                }

                if (_.isString(this.route.meta.title)) {
                    return this.route.meta.title
                }

                return null
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>