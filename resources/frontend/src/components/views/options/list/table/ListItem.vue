<template>

    <tr class="drag">
        <td class="text-center" :class="d_id !== 'move' ? 'drag' : ''" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'move'">
                <span><i class="fa fa-arrows" aria-hidden="true"></i></span>
            </template>

            <template v-if="dimension.id === 'sort_id'">
                {{ item.sortId }}
            </template>

            <template v-if="dimension.id === 'name'">
                <i class="fa fa-cube fa-success" v-if="item.optionCatalogId"></i> {{ item.name }}
                <span class="label label-danger" v-if="item.isDefault">
                    Default
                </span>
            </template>

            <template v-if="dimension.id === 'description'">
                {{ item.description }}
            </template>

            <template v-if="dimension.id === 'unit_price'">
                <span class="money-cell">
                    {{ item.sUnitPrice !== undefined ? filters.money(item.sUnitPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'is_active'">
                <span class="label label-success" v-if="item.isActive === 'yes' ">Yes</span>
                <span class="label label-danger" v-if="item.isActive === 'no' ">No</span>
            </template>

            <template v-if="dimension.id === 'force_qty'">
                <span class="label label-default" v-if="item.forceQuantityFlag">
                    {{ item.constraintTypeFlag.name }} {{ item.forceQuantityFlag.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>
        </td>

        <td class="text-center nowrap drag">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import BuildingStatusLabel from 'src/components/views/partials/BuildingStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            BuildingStatusLabel
        },
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

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    i.fa-success {
        font-size: 18px;
        color: #2a5b8c;
    }
</style>