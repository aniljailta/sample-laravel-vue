<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'name'">
                {{ item.name }}
            </template>

            <template v-if="dimension.id === 'option_id'">
                <span v-if="item.option">
                    {{ item.option.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'width'">
                {{ item.width }}
            </template>

            <template v-if="dimension.id === 'style_id'">
                {{ item.style.name }}
            </template>

            <template v-if="dimension.id === 'length'">
                {{ item.length }}
            </template>

            <template v-if="dimension.id === 'wall_height'">
                {{ item.wallHeight }}
            </template>

            <template v-if="dimension.id === 'shell_price'">
                <span class="money-cell">
                    {{ item.sShellPrice !== undefined ? filters.money(item.sShellPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'is_active'">
                <span class="label label-success" v-if="item.isActive === 'yes' ">Yes</span>
                <span class="label label-danger" v-if="item.isActive === 'no' ">No</span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
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