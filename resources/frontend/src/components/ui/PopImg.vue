<template>
    <div :style="{ width: width, height: height }">
        <img v-bind:width="width" v-bind:height="height" v-bind:src="src">
    </div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                show: false
            }
        },
        props: ['source', 'src'],
        computed: {
            width() {
                return this.source.width + 'px'
            },
            height() {
                return this.source.height + 'px'
            }
        },
        methods: {
            initiate() {
                if (!this.show) this.show = true
            }
        }
    }
</script>

<style type="text/css">

</style>