<template>
    <ul class="nav nav-main">
        <v-nav @refresh="refreshScroller" ref="navSystem" :children="navSystem">
            <a slot="parent" @click="$refs.navSystem.toggle()"><i class="fa fa-cogs"></i> <span>System</span></a>
            <ul slot="children" class="nav nav-children">
                <li :class="{'nav-active': $route.name === 'building-statuses'}">
                    <router-link :to="{name: 'building-statuses'}"><span>Building Statuses</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'option-categories'}">
                    <router-link :to="{name: 'option-categories'}"><span>Option Categories</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'style-catalog'}">
                    <router-link :to="{name: 'style-catalog'}"><span>Style Catalog</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'color-catalog'}">
                    <router-link :to="{name: 'color-catalog'}"><span>Color Catalog</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'option-catalog'}">
                    <router-link :to="{name: 'option-catalog'}"><span>Option Catalog</span></router-link>
                </li>
            </ul>
        </v-nav>
    </ul>
</template>

<script type="text/babel">
    import VNav from 'src/components/ui/Nav.vue'
    export default {
        data() {
            return {
                navSystem: [
                    'building-statuses',
                    'option-categories',
                    'style-catalog',
                    'color-catalog',
                    'option-catalog'
                ]
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