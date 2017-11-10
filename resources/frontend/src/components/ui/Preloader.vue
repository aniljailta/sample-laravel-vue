<template>
    <div class="container">
        <div class="content">
            <i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
            <br>
            <h6>Loading...</h6>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'preloader',
        props: {},
        data() {
            return {}
        },
        computed: {},
        methods: {
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .container {
        display: table;
        width: 100%;
        height: 100%;
        background: #1d2127;
        color: #ecedf0;
    }

    .content {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
    }
</style>