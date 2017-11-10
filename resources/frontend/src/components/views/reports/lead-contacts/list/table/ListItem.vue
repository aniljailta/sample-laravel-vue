<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'date_created'">
                <span v-if="item.createdAt" class="label label-default">
                    {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'order_id'">{{ item.orderId }}</template>
            <template v-if="dimension.id === 'customer'">
                <span v-if="item.customer">
                    {{ item.customer.firstName }} {{ item.customer.lastName }}
                </span>
            </template>
            <template v-if="dimension.id === 'initial_contact'">{{ item.initialContact }}</template>
            <template v-if="dimension.id === 'date_expiration'">
                 <span v-if="item.expiredAt.date" class="label label-default">
                    {{ filters.moment(item.expiredAt.date, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'order_submit'">{{ item.orderSubmit }}</template>
            <template v-if="dimension.id === 'total_price'">
                {{ (item.building && item.building.totalPrice !== undefined) ? filters.money(item.building.totalPrice) : '' }}
            </template>
            <template v-if="dimension.id === 'date_submitted'" class="label label-default">
                <span v-if="item.order && item.order.dateSubmitted" class="label label-default">
                    {{ filters.moment(item.order.dateSubmitted, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItem from 'src/components/views/_base/ListItems/list/ListItem.vue'

    export default {
        extends: baseListItem,
        data() {
            return {}
        },
        components: {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>