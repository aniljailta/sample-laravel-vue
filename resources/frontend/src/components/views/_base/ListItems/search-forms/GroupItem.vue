<template>
    <li class="list-group-item">

        <label>{{ label }}</label>
        <div class="div-close">
            <button type="button" class="close" aria-label="Close" v-on:click="close">
                <span aria-hidden="true">remove &times;</span>
            </button>
        </div>

        <div class="filter-selector">
            <div class="input-group-fix">
                <slot></slot>
            </div>
            <div class="clearfix"></div>
        </div>

    </li>
</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        props: {
            label: {
                default: ''
            }
        },
        components: {},
        methods: {
            close() {
                this.$emit('close')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .list-tools-form {
        .list-group-item .div-close {
            float: right;
        }
        .list-group-item .close {
            font-size: 0.75em !important;
        }

        .form-control {
            display: inline-block;
            vertical-align: middle;
        }
    }
</style>