<template>
    <ul class="nav nav-main">
        <li :class="{'nav-active': $route.name === 'dashboard'}">
            <router-link :to="{name: 'dashboard'}"><i class="fa fa-home"></i> <span>Dashboard</span></router-link>
        </li>
    </ul>
</template>

<script type="text/babel">
    import VNav from 'src/components/ui/Nav.vue'
    export default {
        data() {
            return {
            }
        },
        components: {
            VNav
        },
        props: {},
        methods: {
            refreshScroller() {
                this.$emit('refresh-scroller')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>