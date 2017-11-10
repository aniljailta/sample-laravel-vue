<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'date_submitted'">
                {{ filters.moment(item.dateSubmitted, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'date_updated'">
                {{ filters.moment(item.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'status_id'">
                <order-status-label :status="item.status"></order-status-label>
            </template>

            <template v-if="dimension.id === 'building_model'">
                <span v-if="item.building && item.building.buildingModel">
                    {{ item.building.buildingModel.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'serial_numbers'">
                <span v-if="item.building && item.building.serialNumber">
                    {{ item.building.serialNumber }}
                </span>
            </template>

            <template v-if="dimension.id === 'retail'">
                <span class="money-cell">
                    {{ item.retail !== undefined ? filters.money(item.retail) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'order_type'">
                <span v-if="item.orderType">{{ item.orderType.title }}</span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.dealer">{{ item.dealer.businessName }}</span>
            </template>

            <template v-if="dimension.id === 'sales_person'">
                <span>{{ item.salesPerson }}</span>
            </template>

            <template v-if="dimension.id === 'amount_received'">
                <span class="money-cell">
                    {{ filters.money(item.amountReceived) }}
                </span>
            </template>

            <template v-if="dimension.id === 'sales_tax'">
                <span>{{ item.salesTax }}</span>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.orderReference">
                    {{ item.orderReference.firstName }} {{ item.orderReference.lastName }}
                </span>
            </template>

            <template v-if="dimension.id === 'payment_type'">
                <span v-if="item.paymentType">{{ item.paymentType }}</span>
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

        <td class="text-center nowrap" v-if="dimensions.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
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
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>