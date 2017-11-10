<template>

    <tr class="drag">
        <td class="text-center" :class="d_id !== 'move' || item.isSystem !== 0 ? 'drag' : ''" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'move'">
                <span v-if="item.isSystem == 0"><i class="fa fa-arrows" aria-hidden="true"></i></span>
            </template>

            <template v-if="dimension.id === 'priority'">
                {{ item.priority }}
            </template>

            <template v-if="dimension.id === 'name'">
                {{ item.name }}
            </template>

            <template v-if="dimension.id === 'is_active'">
                <span class="label label-success" v-if="item.isActive === 'yes' ">Yes</span>
                <span class="label label-danger" v-if="item.isActive === 'no' ">No</span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>
        </td>

        <td class="text-center nowrap drag">
            <button class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal" :disabled="item.isSystem == 1"><i class="fa fa-pencil"></i></button>
            <button class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal" :disabled="item.isSystem == 1"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {},
        methods: {
            openUpdateModal() {
                this.$parent.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'edit'
                })
            },
            openDeleteModal() {
                this.$parent.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'delete'
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>