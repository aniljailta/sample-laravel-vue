<template>
    <li class="nav-parent" :class="{'nav-expanded': expanded}">
        <slot name="parent"/>
        <slot name="children"/>
    </li>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                expanded: false
            }
        },
        components: {},
        props: {
            children: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        created() {
            this.checkRoute(this.routeName)
            this.$watch('routeName', (name) => {
                this.checkRoute(name)
            })
        },
        computed: {
            routeName() {
                return this.$route.name
            }
        },
        methods: {
            checkRoute(name) {
                if (this.children.indexOf(name) !== -1) {
                    this.expand()
                }
            },
            expand() {
                this.expanded = true
                this.$nextTick(() => {
                    this.$emit('refresh')
                })
            },
            toggle() {
                this.expanded = !this.expanded
                this.$nextTick(() => {
                    this.$emit('refresh')
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>