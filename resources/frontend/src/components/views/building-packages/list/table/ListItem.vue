<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'building_model_id'">
                {{ item.buildingModel ? item.buildingModel.name : '' }}
            </template>

            <template v-if="dimension.id === 'category_id'">
                {{ item.category ? item.category.name : '' }}
            </template>

            <template v-if="dimension.id === 'name'">
                {{ item.name }}
            </template>

            <template v-if="dimension.id === 'total_price'">
                <span class="money-cell">
                    {{ item.sTotalPrice !== undefined ? filters.money(item.sTotalPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'is_active'">
                <span class="label label-success" v-if="item.isActive === 'yes' ">Yes</span>
                <span class="label label-danger" v-if="item.isActive === 'no' ">No</span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'style_id'">
                <span v-if="item.buildingModel.style">
                    {{ item.buildingModel.style.name }}
                </span>
            </template>
        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
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
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>