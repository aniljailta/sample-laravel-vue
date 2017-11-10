<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'sale_id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'order_id'">{{ item.orderId }}</template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'date_submitted'">
                <span v-if="item.order">{{ item.order.dateSubmitted }}</span>
            </template>

            <template v-if="dimension.id === 'status_id'">
                <sale-status-label :status="item.status" v-if="item.status"></sale-status-label>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.order && item.order.orderReference">{{ item.order.orderReference.customerName }}</span>
            </template>

            <template v-if="dimension.id === 'payment_type'">
                <span v-if="item.order && item.order.paymentTypeData">{{ item.order.paymentTypeData.title }}</span>
            </template>

            <template v-if="dimension.id === 'order_type'">
                <span v-if="item.order && item.order.orderType">{{ item.order.orderType.title }}</span>
            </template>

            <template v-if="dimension.id === 'amount_received'">
                <span class="money-cell" v-if="item.order">
                    {{ item.order.amountReceived !== undefined ? filters.money(item.order.amountReceived) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'building_sn'">
                <span v-if="item.building">
                    <router-link :to="{ name: 'building.show', params: { id: item.building.id }}" target="_blank">
                        {{ item.building.serialNumber }}
                    </router-link>
                </span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.order && item.order.dealer">{{ item.order.dealer.businessName }}</span>
            </template>

            <template v-if="dimension.id === 'invoice_id'">
                {{ item.invoiceId }}
            </template>

            <template v-if="dimension.id === 'dealer_commission'">
                <span class="money-cell">{{ item.dealerCommission !== undefined ? filters.money(item.dealerCommission) : '' }}</span>
            </template>

            <template v-if="dimension.id === 'retail'">
                <span class="money-cell">{{ item.retail !== undefined ? filters.money(item.retail) : '' }}</span>
            </template>

            <template v-if="dimension.id === 'sales_tax'">
                <span class="money-cell">{{ item.salesTax !== undefined ? filters.money(item.salesTax) : '' }}</span>
            </template>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItem from 'src/components/views/_base/ListItems/list/ListItem.vue'
    import SaleStatusLabel from 'src/components/views/partials/SaleStatusLabel.vue'

    export default {
        extends: baseListItem,
        data() {
            return {
            }
        },
        components: {
            SaleStatusLabel
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>