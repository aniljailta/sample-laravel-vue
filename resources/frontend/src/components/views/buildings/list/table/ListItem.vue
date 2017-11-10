<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'sort_id'">{{ item.sortId }}</template>

            <template v-if="dimension.id === 'plant_id'">{{ item.plant.plantId }}</template>
            <template v-if="dimension.id === 'manufacture_year'">{{ item.manufactureYear }}</template>

            <template v-if="dimension.id === 'serial_number'">
                <router-link :to="{ name: 'building.show', params: { id: item.id }}">
                    {{ item.serialNumber }}
                </router-link>
            </template>

            <template v-if="dimension.id === 'build_status'">
                <building-status-label :status="item.lastStatus.buildingStatus" v-if="item.lastStatus"></building-status-label>
            </template>

            <template v-if="dimension.id === 'total_price'">
                <span class="money-cell">
                    {{ item.bTotalPrice !== undefined ? filters.money(item.bTotalPrice) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'order_id'">
                <a :style="{cursor: 'pointer'}" v-on:click="openOrderUpdateModal(item.order)">{{ item.orderId }}</a>
            </template>

            <template v-if="dimension.id === 'sale_id'">
                <span v-if="item.order && item.order.sale">
                    <a :style="{cursor: 'pointer'}" v-on:click="openSaleUpdateModal(item.order.sale)">{{ item.order.sale.id }}</a>
                </span>
            </template>

            <template v-if="dimension.id === 'customer'">
                <span v-if="item.order && item.order.orderReference">{{ item.order.orderReference.customerName }}</span>
            </template>

            <template v-if="dimension.id === 'location'">
                <span v-if="item.lastLocation && item.lastLocation.location">{{ item.lastLocation.location.name }}</span>
            </template>

            <template v-if="dimension.id === 'roof_color'">
                <span v-if="item.roof">{{ item.roof.color.name }}</span>
            </template>
            <template v-if="dimension.id === 'trim_color'">
                <span v-if="item.trim">{{ item.trim.color.name }}</span>
            </template>
            <template v-if="dimension.id === 'siding_color'">
                <span v-if="item.siding">{{ item.siding.color.name }}</span>
            </template>

            <template v-if="dimension.id === 'date_sold'">
                <span v-if="item.order && item.order.sale" class="label label-default">
                    {{ filters.moment(item.order.sale.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
            </template>

            <template v-if="dimension.id === 'style_id'">
                <span v-if="item.buildingModel && item.buildingModel.style">{{ item.buildingModel.style.name }}</span>
            </template>

            <template v-if="dimension.id === 'model_id'">
                <span v-if="item.buildingModel">{{ item.buildingModel.name }}</span>
            </template>

            <template v-if="dimension.id === 'building_package_id'">
                <span v-if="item.buildingPackage">{{ item.buildingPackage.name }}</span>
            </template>

            <template v-if="dimension.id === 'width'">{{ item.width }}</template>

            <template v-if="dimension.id === 'length'">{{ item.length }}</template>

            <template v-if="dimension.id === 'payment_method'">
                <span v-if="item.order && item.order.paymentTypeData">{{ item.order.paymentTypeData.title }}</span>
            </template>

            <template v-if="dimension.id === 'amount_received'">
                <span class="money-cell" v-if="item.order">
                    {{ item.order.amountReceived !== undefined ? filters.money(item.order.amountReceived) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'invoice_id'">
                <span v-if="item.order && item.order.sale">{{ item.order.sale.invoiceId }}</span>
            </template>

            <template v-if="dimension.id === 'used'">
                <span>{{ item.usedRtoOwner ? 'yes' : 'no' }}</span>
            </template>

            <template v-if="dimension.id === 'sold_status'">
                <span>{{ item.soldStatus }}</span>
            </template>

            <template v-if="dimension.id === 'sales_tax'">
                <span class="money-cell" v-if="item.order">{{ item.salesTax !== undefined ? filters.money(item.salesTax) : '' }}</span>
            </template>

            <template v-if="dimension.id === 'dealer'">
                <span v-if="item.order && item.order.dealer">{{ item.order.dealer.businessName }}</span>
            </template>
        </td>

        <td class="text-center nowrap" v-if="item.id">
            <router-link :to="{ name: 'building.show', params: { id: item.id }}" class="pointer btn btn-default btn-xs">
                <i class="fa fa-eye"></i>
            </router-link>
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-info btn-xs" v-on:click="openDuplicateModal"><i class="fa fa-clone"></i></a>
            <a v-if="item.soldStatus=='sold'" class="pointer btn btn-warning btn-xs" v-on:click="openReturnModal"><i class="fa fa-reply"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import BuildingStatusLabel from 'src/components/views/partials/BuildingStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {
                order: this.order
            }
        },
        components: {
            BuildingStatusLabel
        },
        created() {},
        methods: {
            openOrderUpdateModal(order) {
                this.$parent.$parent.$emit('change-entry', {
                    mode: 'editOrder',
                    item: order
                })
            },
            openSaleUpdateModal(sale) {
                this.$parent.$parent.$emit('change-entry', {
                    mode: 'editSale',
                    item: sale
                })
            },
            openDuplicateModal() {
                let item = _.cloneDeep(this.item)
                this.$parent.$parent.$emit('change-entry', {
                    item: item,
                    mode: 'duplicate'
                })
            },
            openReturnModal() {
                let item = _.cloneDeep(this.item)
                this.$parent.$parent.$emit('change-entry', {
                    item: item,
                    mode: 'return'
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>