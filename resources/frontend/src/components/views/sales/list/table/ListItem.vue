<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>
            <template v-if="dimension.id === 'order_id'">
                <a :style="{cursor: 'pointer'}" v-on:click="openOrderUpdateModal(item.order)">{{ item.orderId }}</a>
            </template>
            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'status_id'">
                <sale-status-label :status="item.status"></sale-status-label>
            </template>

            <template v-if="dimension.id === 'building_sn'">
                <span v-if="item.building">
                    <router-link :to="{ name: 'building.show', params: { id: item.building.id }}" target="_blank">
                        {{ item.building.serialNumber }}
                    </router-link>
                </span>
            </template>

            <template v-if="dimension.id === 'retail'">
                <span class="money-cell" v-if="item.order">
                    {{ item.order.totalSalesPrice !== undefined ? filters.money(item.order.totalSalesPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'order_type'">
                <span v-if="item.order && item.order.orderType">{{ item.order.orderType.title }}</span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.order && item.order.dealer">{{ item.order.dealer.businessName }}</span>
            </template>

            <template v-if="dimension.id === 'sales_person'">
                <span v-if="item.order">{{ item.order.salesPerson }}</span>
            </template>

            <template v-if="dimension.id === 'date_submitted'">
                <span v-if="item.order">{{ item.order.dateSubmitted }}</span>
            </template>

            <template v-if="dimension.id === 'amount_received'">
                <span class="money-cell" v-if="item.order">
                    {{ item.order.amountReceived !== undefined ? filters.money(item.order.amountReceived) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'sales_tax'">
                <span v-if="item.order">{{ item.order.salesTax }}</span>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.order && item.order.orderReference">
                    {{ item.order.orderReference.firstName }} {{ item.order.orderReference.lastName }}
                </span>
            </template>

            <template v-if="dimension.id === 'invoice_id'">
                {{ item.invoiceId }}
            </template>

            <template v-if="dimension.id === 'building_location'">
                <span v-if="item.building && item.building.lastLocation && item.building.lastLocation.location">
                    {{ item.building.lastLocation.location.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'delivery_id'">
                <span v-if="item.delivery">
                    {{ item.delivery.id }}
                </span>
            </template>

            <template v-if="dimension.id === 'delivery_status'">
                <span v-if="item.delivery">
                    <delivery-status-label :status="item.delivery.status"></delivery-status-label>
                </span>
            </template>

            <template v-if="dimension.id === 'payment_type'">
                <span v-if="item.order && item.order.paymentType">{{ item.order.paymentType }}</span>
            </template>

            <template v-if="dimension.id === 'attachments'">
                <button type="button"
                        class="btn btn-primary btn-sm"
                        v-bind:disabled="!item.order.files || item.order.files.length === 0"
                        v-on:click="showAttachments">

                    <i class="fa fa-files-o" aria-hidden="true"></i>
                    <span class="label label-default">
                    {{ item.order.files ? item.order.files.length : 0 }}
                </span>

                </button>
            </template>
        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import SaleStatusLabel from 'src/components/views/partials/SaleStatusLabel.vue'
    import DeliveryStatusLabel from 'src/components/views/partials/DeliveryStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            SaleStatusLabel,
            DeliveryStatusLabel
        },
        methods: {
            openOrderUpdateModal(order) {
                this.$parent.$parent.$emit('change-entry', {
                    mode: 'editOrder',
                    item: order
                })
            },
            showAttachments() {
                this.$parent.$parent.$emit('change-entry', {
                    mode: 'attachments',
                    item: this.item
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>