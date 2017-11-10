<template>

    <div class="order-note-admin-panel">
        <div class="panel panel-warning">
            <div class="panel-heading">Admin Notes</div>
            <div class="panel-body">
                {{ note }}
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
          return {
              noteDealer: null
          }
        },
        props: {
            note: {
                default: ''
            }
        },
        computed: {
        },
        methods: {
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .order-state {
        margin-top: -1px;
    }

    .panel {
        border-radius: 0px;
        margin-bottom: 0.5em;
    }
    .panel-heading {
        border-radius: 0px;
    }
    .panel-body {
        padding: 0px;
    }
    .order-note-admin-panel {
        padding-bottom: 0px !important;
        padding-top: 0px !important;

        .panel-body {
            padding: 0.5em !important;
        }
    }
</style>