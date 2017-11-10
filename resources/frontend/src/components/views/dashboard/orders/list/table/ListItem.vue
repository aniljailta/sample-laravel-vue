<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'status_id'">
                <order-status-label :status="item.status"></order-status-label>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.orderReference">
                    {{ item.orderReference.firstName }} {{ item.orderReference.lastName }}
                </span>
            </template>

            <template v-if="dimension.id === 'total_sales_price'">
                <span v-if="item.totalSalesPrice">
                    {{ item.totalSalesPrice !== undefined ? filters.money(item.totalSalesPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'payment_type'">
                <span v-if="item.paymentType">
                    {{ item.paymentType === 'rto' ? 'RTO' : 'Purchase Outright' }}
                </span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.dealer">{{ item.dealer.businessName }}</span>
            </template>

            <template v-if="dimension.id === 'sales_person'">
                <span>{{ item.salesPerson }}</span>
            </template>

            <template v-if="dimension.id === 'attachments'">
                <button type="button"
                        class="btn btn-primary btn-sm"
                        v-bind:disabled="!item.files || item.files.length === 0"
                        v-on:click="showAttachments">

                    <i class="fa fa-files-o" aria-hidden="true"></i>
                    <span class="label label-default">
                        {{ item.files ? item.files.length : 0 }}
                    </span>
                </button>
            </template>
        </td>

        <td class="text-center nowrap">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-primary btn-xs" v-on:click="openDealerOrderModal"><i class="fa fa-file"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            OrderStatusLabel
        },
        methods: {
            showAttachments() {
                this.$parent.$parent.$emit('change-entry', {
                    mode: 'attachments',
                    item: this.item
                })
            },
            openDealerOrderModal() {
                this.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'dealer_order'
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>