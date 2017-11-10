<template>
    <router-view v-if="ready" v-title="title"/>
    <v-preloader v-else/>
</template>

<script type="text/babel">
    import {mapActions, mapState, mapGetters} from 'vuex'
    import VPreloader from 'src/components/ui/Preloader.vue'

    export default {
        data() {
            return {
                ready: false
            }
        },
        components: {VPreloader},
        props: {},
        beforeCreate() {},
        created() {
            this.initDependencies()
        },
        destroyed() {},
        computed: {
            ...mapState({
               route: state => state.route
            }),
            ...mapGetters({
                'settings': 'settings/all'
            }),
            title() {
                let pageTitle = []

                if (this.settings.name) {
                    pageTitle.push(this.settings.name)
                }

                _.each(this.$route.matched, (route) => {
                    if (!_.isEmpty(route.meta.title)) {
                        pageTitle.push(route.meta.title)
                    }
                })

                return pageTitle.join(' - ')
            }
        },
        methods: {
            ...mapActions({
                fetchUser: 'user/fetchUser',
                fetchSettings: 'settings/fetchSettings'
            }),
            initDependencies() {
                Promise.all([
                    this.fetchUser(),
                    this.fetchSettings()
                ]).then(response => {
                    this.ready = true
                })
            }
        }
    }
</script>

<style type="text/css">

</style>