<template>
    <component :is="view"/>
</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    export default {
        data() {
            return {}
        },
        components: {
            manufacturerAdministratorDashboard: resolve => {
                require(['./manufacturer/admin'], resolve)
            }
        },
        computed: {
            ...mapGetters({
                'user': 'user/user',
                'role': 'user/role'
            }),
            view() {
                return 'manufacturer-' + this.role.name + '-dashboard'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>