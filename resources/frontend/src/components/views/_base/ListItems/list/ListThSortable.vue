<template>
    <th class="text-center pointer" v-bind:class="getClass('head')" v-on:click="orderBy">
        <a>{{ dimension.name }}</a> <i v-bind:class="getClass('icon')" class="fa" aria-hidden="true"></i>
    </th>
</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        props: {
            dimension: {
                required: true
            }
        },
        components: {},
        methods: {
            getClass(type) {
                return this.$parent.getClass(this.dimension.id, type)
            },
            orderBy() {
                return this.$parent.orderBy(this.dimension.id)
            }
        }
    }
</script>

<style type="text/css">

</style>