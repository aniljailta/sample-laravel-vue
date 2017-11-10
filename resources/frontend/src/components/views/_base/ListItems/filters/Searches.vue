<template>


    <div class="btn-group-xs">
        <label class="btn btn-default"
               v-for="(item, item_id) in items"
               v-if="!items[item_id].hidden"
               v-bind:class="[items[item_id] && items[item_id].checked ? 'active' : '']">
            <input type="checkbox"
                   v-bind:name="item.id"
                   v-on:click="toggleCheckbox(item)">
            {{ item.name }}
        </label>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        components: {},
        props: {
            // { name: 'Item name', id: 'item_id' }
            items: {
                required: true,
                default() {
                    return {}
                }
            }
        },
        computed: {
        },
        methods: {
            toggleCheckbox(item) {
                let it = _.cloneDeep(item)
                it.checked = !it.checked
                this.$emit('update-search', it)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    label.btn > input[type='checkbox'] {
        display: none;
    }
    .btn.active {
        background-color: #47a447;
        color: white;
    }
</style>