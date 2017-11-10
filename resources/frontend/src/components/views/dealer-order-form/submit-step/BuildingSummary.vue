<template>

    <div>
        <h4 class="text-center">Order Summary</h4>
        <div class="list-group">

            <!-- Order Total -->
            <div class="list-group-item list-group-item-success lead" v-if="order.currentTotal > 0">
                <div class="row">
                    <div class="col-xs-6 text-right">Retail Price:</div>
                    <div class="col-xs-6"><strong>{{ order.currentTotal | money }}</strong></div>
                </div>
            </div>

            <!-- (cash) Sales Tax -->
            <div class="list-group-item list-group-item-info lead" v-if="order.paymentType === 'cash'">
                <div class="row">
                    <div class="col-xs-6 text-right">Sales Tax:</div>
                    <div class="col-xs-6"><strong>{{ order.salesTax | money }}</strong></div>
                </div>
            </div>

            <!-- (rto) Security Deposit -->
            <div class="list-group-item list-group-item-info lead"
                 v-if="order.paymentType === 'rto' && order.currentTotal > 0">
                <div class="row">
                    <div class="col-xs-6 text-right">Security Deposit:</div>
                    <div class="col-xs-6"><strong>{{ order.securityDeposit | money }}</strong></div>
                </div>
            </div>

            <!-- (rto) Gross buydown -->
            <div class="list-group-item list-group-item-info lead"
                 v-if="order.paymentType === 'rto' && order.rtoType === 'buydown' ">
                <div class="row">
                    <div class="col-xs-6 text-right">Gross buydown:</div>
                    <div class="col-xs-6"><strong>{{ order.grossBuydown | money }}</strong></div>
                </div>
            </div>

            <!-- (rto) Net buydown -->
            <div class="list-group-item list-group-item-info lead"
                 v-if="order.paymentType === 'rto' && order.rtoType === 'buydown' ">
                <div class="row">
                    <div class="col-xs-6 text-right">Net Buydown:</div>
                    <div class="col-xs-6"><strong>{{ order.netBuydown | money }}</strong></div>
                </div>
            </div>

            <!-- (rto) Total Advanced Monthly Payment (rtoPayment) -->
            <div class="list-group-item list-group-item-info lead"
                 v-if="order.paymentType === 'rto' && order.currentTotal > 0">
                <div class="row">
                    <div class="col-xs-6 text-right">
                        <span class="hidden-xs hidden-md">Rent-to-Own Payment:</span>
                        <span class="hidden-sm hidden-lg">RTO Payment:</span>
                    </div>
                    <div class="col-xs-6"><strong>{{ order.rtoPayment | money }}</strong></div>
                </div>
            </div>

            <!-- Deposit Amount -->
            <div class="list-group-item list-group-item-info lead" v-if="order.paymentType !== null">
                <div class="row">
                    <div class="col-xs-6 text-right">Required Deposit:</div>
                    <div class="col-xs-6"><strong>{{ order.totalAmountDue | money }}</strong> <br/> <small>(minimum required)</small></div>
                </div>
            </div>

            <div class="list-group-item text-center" v-if="order.currentTotal > 0">
                <small>Retail price includes delivery and setup (taxes not included)</small>
            </div>

            <div class="list-group-item">
                Payment Type <span class="badge">{{order.currentPaymentType }}</span>
            </div>
            <div class="list-group-item" v-if="order.paymentType === 'rto'">
                Rent-To-Own Method <span class="badge">{{ order.currentRtoType }}</span>
            </div>
            <div class="list-group-item" v-if="order.paymentType === 'rto'">
                Rent-To-Own Terms <span class="badge">{{ order.currentRtoTerm.name }}</span>
            </div>
            <div class="list-group-item" v-if="order.paymentType === 'rto' && order.rtoType === 'buydown'">
                Gross Buydown <span class="badge">{{ order.grossBuydown | money }}</span>
            </div>
            <div class="list-group-item" v-if="order.paymentType !== null">
                Deposit Received <span class="badge">{{ order.amountReceived | money }}</span>
            </div>
            <div class="list-group-item">
                Payment method <span class="badge">{{ order.currentPaymentMethod }}</span>
            </div>
            <div class="list-group-item"
                 v-if="order.paymentMethod === 'check' || order.paymentMethod === 'credit_card'">
                Transaction ID <span class="badge">{{ order.transactionId }}</span>
            </div>
        </div>
        <h4 class="text-center">Building Summary</h4>
        <div class="list-group">

            <div class="list-group-item" v-if="building.saleType !== null">
                Sale Type <span class="badge">{{ building.currentSaleType }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'dealer-inventory'">
                Building <span class="badge">{{ building.inventoryBuilding.serial }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'dealer-inventory'">
                Condition <span class="badge">{{ building.buildingCondition }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Building Style <span class="badge">{{ building.buildingStyle.name }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Building Dimension <span class="badge">{{ building.buildingDimension.width
                }}x{{ building.buildingDimension.length }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Body Color <span class="badge">{{ sidingOption ? sidingOption.color.name : '' }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Trim Color <span class="badge">{{ trimOption ? trimOption.color.name : '' }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Roof Color <span class="badge">{{ roofOption ? roofOption.color.name : '' }}</span>
            </div>
            <div class="list-group-item" v-if="building.saleType === 'custom-order'">
                Custom Options <span class="badge">{{ building.customBuildOptions.length }} option(s) selected</span>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'

    export default {
        name: 'building-summary',
        computed: {
            ...mapGetters({
                building: 'dealerOrderForm/orderSummaryBuilding',
                order: 'dealerOrderForm/orderSummaryOrder',
                optionCategories: 'dealerOrderForm/options/categories'
            }),
            roofOption() {
                let category = _.find(this.optionCategories, (cat) => cat.group === 'roof')
                if (!category) return null

                let option = _.find(this.building.customBuildOptions, (item) => item.option.categoryId === category.id)
                if (!option) return null
                return option
            },
            trimOption() {
                let category = _.find(this.optionCategories, (cat) => cat.group === 'trim')
                if (!category) return null

                let option = _.find(this.building.customBuildOptions, (item) => item.option.categoryId === category.id)
                if (!option) return null
                return option
            },
            sidingOption() {
                let category = _.find(this.optionCategories, (cat) => cat.group === 'siding')
                if (!category) return null

                let option = _.find(this.building.customBuildOptions, (item) => item.option.categoryId === category.id)
                if (!option) return null
                return option
            }
        }
    }
</script>

<style type="text/css">

</style>