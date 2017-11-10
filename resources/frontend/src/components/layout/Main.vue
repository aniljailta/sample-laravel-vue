<template>
    <section class="body" :class="bodyClass">
        <v-header :sideBarOpened.sync="sideBarOpened"/>

        <div class="inner-wrapper">
            <v-sidebar :collapsed.sync="sideBarCollapsed"/>

            <section role="main" class="content">

                <header class="page-header">
                    <h2>{{ $route.meta.title }}</h2>
                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="/#/"><i class="fa fa-home"></i></a>
                            </li>
                            <v-breadcrumb :route="route" v-for="(route, index) in $route.matched" :key="index"/>
                        </ol>
                        <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a></div>
                </header>

                <router-view/>
            </section>
        </div>
    </section>
</template>

<script type="text/babel">
    import VHeader from './Header.vue'
    import VSidebar from './Sidebar.vue'
    import VBreadcrumb from './Breadcrumb.vue'

    export default {
        data() {
            return {
                sideBarOpened: false,
                sideBarCollapsed: false
            }
        },
        components: {VHeader, VSidebar, VBreadcrumb},
        computed: {
            bodyClass() {
                let bodyClass = []
                if (this.sideBarCollapsed) {
                    bodyClass.push('sidebar-left-collapsed')
                }
                if (this.sideBarOpened) {
                    bodyClass.push('sidebar-left-opened')
                }
                return bodyClass
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/layout/index.scss"></style>
<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>