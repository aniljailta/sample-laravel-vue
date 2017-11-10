<template>
    <aside id="sidebar-left" class="sidebar-left">

        <div class="sidebar-header">
            <div class="sidebar-title">Menu</div>
            <div class="sidebar-toggle hidden-xs" @click="toggleSideBar">
                <i class="fa fa-bars"></i>
            </div>
        </div>

        <div class="nano" ref="nanoScroller">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                    <component :is="menu" @refresh-scroller="refreshScroller"/>
                </nav>

                <hr class="separator">
            </div>
        </div>
    </aside>
</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    import VNav from 'src/components/ui/Nav.vue'
    export default {
        data() {
            return {}
        },
        components: {
            VNav,
            SuAdministrator: resolve => require(['./menu/super-admin/Administrator.vue'], resolve),
            ManufacturerAdministrator: resolve => require(['./menu/manufacturer/Administrator.vue'], resolve),
            ManufacturerEmployee: resolve => require(['./menu/manufacturer/Employee.vue'], resolve)
        },
        props: {
            collapsed: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            this.$refs.nanoScroller = $(this.$refs.nanoScroller).nanoScroller({
                alwaysVisible: false,
                preventPageScrolling: true
            })
        },
        computed: {
            ...mapGetters({
                'role': 'user/role',
                'company': 'user/company'
            }),
            menu() {
                if (this.company.roleId === 'super_admin') {
                    let prefix = 'Su'
                    if (this.role.name === 'administrator') return prefix + 'Administrator'
                }
                if (this.company.roleId === 'manufacturer') {
                    let prefix = 'Manufacturer'
                    if (this.role.name === 'administrator') return prefix + 'Administrator'
                    if (this.role.name === 'employee') return prefix + 'Employee'
                }
            }
        },
        methods: {
            toggleSideBar(event) {
                this.$emit('update:collapsed', !this.collapsed)
            },
            refreshScroller() {
                this.$refs.nanoScroller.nanoScroller()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>