<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">
                {{ item.id }}
            </template>

            <template v-if="dimension.id === 'invoice_id'">
                <span>{{ item.invoiceId }}</span>
            </template>

            <template v-if="dimension.id === 'paymentable_id'">
                <span>{{ item.paymentableId }}</span>
            </template>

            <template v-if="dimension.id === 'status'">
                <payment-status-label :status="item.status"></payment-status-label>
            </template>

            <template v-if="dimension.id === 'amount'">
                {{ item.amount !== undefined ? filters.money(item.amount) : '' }}
            </template>

            <template v-if="dimension.id === 'payment_method'">
                {{ item.paymentMethod === 'cash' ? 'Cash' : item.paymentMethod === 'check' ? 'Check' : item.paymentMethod === 'credit_card' ? 'Credit Card' : item.paymentMethod === 'ach' ? 'ACH' : 'Wire Transfer' }}
            </template>

            <template v-if="dimension.id === 'transaction_id'">
                {{ item.transactionId }}
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
    import PaymentStatusLabel from 'src/components/views/partials/PaymentStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            PaymentStatusLabel
        },
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>