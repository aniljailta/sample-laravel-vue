<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'date_updated'">
                {{ filters.moment(item.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'first_name'">
                <span>{{ item.firstName }}</span>
            </template>

            <template v-if="dimension.id === 'last_name'">
                <span>{{ item.lastName }}</span>
            </template>

            <template v-if="dimension.id === 'email'">
                <span>{{ item.email }}</span>
            </template>

            <template v-if="dimension.id === 'phone'">
                <span>{{ item.phone }}</span>
            </template>

            <template v-if="dimension.id === 'address'">
                <span>{{ item.address }}</span>
            </template>

            <template v-if="dimension.id === 'city'">
                <span>{{ item.city }}</span>
            </template>

            <template v-if="dimension.id === 'state'">
                <span>{{ item.state }}</span>
            </template>

            <template v-if="dimension.id === 'zip'">
                <span>{{ item.zip }}</span>
            </template>
        </td>

        <td class="text-center nowrap" v-if="item.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
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
        methods: {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>