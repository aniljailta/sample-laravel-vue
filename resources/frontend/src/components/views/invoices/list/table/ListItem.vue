<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">
                {{ item.id }}
            </template>

            <template v-if="dimension.id === 'invoiceable_id'">
                <span>{{ item.invoiceableId }}</span>
            </template>

            <template v-if="dimension.id === 'amount'">
                {{ item.amount !== undefined ? filters.money(item.amount) : '' }}
            </template>

            <template v-if="dimension.id === 'invoice_number'">
                <span>{{ item.invoiceNumber }}</span>
            </template>

            <template v-if="dimension.id === 'status'">
                <span class="label label-success" v-if="item.status === 'open'">Open</span>
                <span class="label label-danger" v-if="item.status === 'closed'">Closed</span>
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
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>