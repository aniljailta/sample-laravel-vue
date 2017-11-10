<template>

    <group-item v-on:close="close" v-bind:label="item.name">
        <div class="btn-group input-buttons">
            <label v-for="data in datas"
                   v-bind:class="[item.value && item.value == data.id ? 'active' : '']"
                   class="btn btn-default btn-sm">
                <input type="radio"
                       name="include_sn"
                       v-bind:value="data.id"
                       v-bind:checked="item.value && item.value == data.id"
                       autocomplete="off"
                       v-on:change="change($event.target.value)"/>
                {{ data.title }}
            </label>
        </div>
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
            },
            datas: {
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