<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'first_name'">
                {{ item.firstName }}
            </template>

            <template v-if="dimension.id === 'last_name'">
                {{ item.lastName }}
            </template>

            <template v-if="dimension.id === 'email'">
                {{ item.email }}
            </template>

        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <router-link :to="{ name: 'employee.show', params: { id: item.id }}" class="pointer btn btn-default btn-xs">
                <i class="fa fa-eye"></i>
            </router-link>
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