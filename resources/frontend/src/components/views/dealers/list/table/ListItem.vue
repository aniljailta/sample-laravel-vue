<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'business_name'">
                {{ item.businessName }}
            </template>

            <template v-if="dimension.id === 'is_active'">
                <active-flag-label v-bind:flag="item.active"></active-flag-label>
            </template>

            <template v-if="dimension.id === 'phone'">
                {{ item.phone }}
            </template>

            <template v-if="dimension.id === 'email'">
                {{ item.email }}
            </template>

            <template v-if="dimension.id === 'ein'">
                {{ item.ein }}
            </template>

            <template v-if="dimension.id === 'tax_rate'">
                {{ item.taxRate }}
            </template>

            <template v-if="dimension.id === 'commission_rate'">
                {{ item.commissionRate }}
            </template>

            <template v-if="dimension.id === 'cash_sale_deposit_rate'">
                {{ item.cashSaleDepositRate }}
            </template>

            <template v-if="dimension.id === 'location_id'">
                <span v-if="item.location">{{ item.location.name }}</span>
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
    import ActiveFlagLabel from 'src/components/views/partials/ActiveFlagLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            ActiveFlagLabel
        },
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>