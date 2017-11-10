<template>

    <group-item v-on:close="close" v-bind:label="item.name">
        <input id="customer"
               name="customer"
               class="form-control input-sm"
               initial="off"
               v-on:input="change($event.target.value)"
               v-bind:value="item.value">
    </group-item>

</template>

<script type="text/babel">
    import GroupItem from './GroupItem.vue'
    export default {
        data() {
            return {}
        },
        props: {
            item: {
                required: true,
                default() {
                    return {}
                }
            }
        },
        components: {
            GroupItem
        },
        methods: {
            close() {
                let item = _.cloneDeep(this.item)
                item.checked = false
                this.$parent.$emit('update-search', item)
            },
            change(value) {
                let item = _.cloneDeep(this.item)
                item.value = value
                this.$parent.$emit('update-search', item)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>