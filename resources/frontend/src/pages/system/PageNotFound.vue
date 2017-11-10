<template>
    <div class="page-container">
        <div class="page-content">
            <h1>Page is not found</h1>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'page-not-found',
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
    .page-container {
        display: table;
        width: 100%;
        height: 100%;
        background: #ecedf0;
        color: #1d2127;
    }

    .page-content {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
    }
</style>