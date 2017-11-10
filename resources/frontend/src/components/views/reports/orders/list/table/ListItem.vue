<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'order_id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'status_id'">
                <order-status-label :status="item.status" v-if="item.status"></order-status-label>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.orderReference">{{ item.orderReference.customerName }}</span>
            </template>

            <template v-if="dimension.id === 'order_type'">
                <span v-if="item.orderType">{{ item.orderType.title }}</span>
            </template>

            <template v-if="dimension.id === 'building_sn'">
                <span v-if="item.building">
                    <router-link :to="{ name: 'building.show', params: { id: item.building.id }}" target="_blank">
                        {{ item.building.serialNumber }}
                    </router-link>
                </span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.dealer">{{ item.dealer.businessName }}</span>
            </template>

            <template v-if="dimension.id === 'retail'">
                <span class="money-cell">{{ item.retail !== undefined ? filters.money(item.retail) : '' }}</span>
            </template>

        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItem from 'src/components/views/_base/ListItems/list/ListItem.vue'
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'

    export default {
        extends: baseListItem,
        data() {
            return {
            }
        },
        components: {
            OrderStatusLabel
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>