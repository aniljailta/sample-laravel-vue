<template>
    <div>
        <!-- Building total -->
        <div class="list-group-item list-group-item-success lead" v-if="totalAmount > 0">
            <div class="row">
                <div class="col-xs-6 text-right">Total Building Price:</div>
                <div class="col-xs-6">
                    <strong>{{ currentBuilding.totalPrice | money }}</strong>
                    <span class="collapse-badge" @click="toggleDetails('showBuildingPrices')">
                        <span v-if="!expensesTool.showBuildingPrices">Details</span>
                        <span v-else>Hide</span>
                    </span>
                </div>
            </div>

            <div class="small" v-show="expensesTool.showBuildingPrices">
                <div class="row">
                    <div class="col-xs-6 text-right">Shell Price:</div>
                    <div class="col-xs-6"><strong>{{ currentBuilding.shellPrice | money }}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-6 text-right">Total Building Options:</div>
                    <div class="col-xs-6"><strong>{{ currentBuilding.totalOptions | money }}</strong></div>
                </div>
            </div>
        </div>

        <div class="list-group-item list-group-item-success lead main">
            <div class="row">
                <div class="col-xs-6 text-right">Order Options:</div>
                <div class="col-xs-6">
                    <strong>{{ totalOrderOptions | money }}</strong>
                    <span class="collapse-badge" @click="toggleDetails('showOrderOptionPrices')">
                        <span v-if="!expensesTool.showOrderOptionPrices">Details</span>
                        <span v-else>Hide</span>
                    </span>
                </div>
            </div>

            <div class="small" v-show="expensesTool.showOrderOptionPrices">

                <div class="row">
                    <div class="col-xs-6 text-right">Taxable Order Options:</div>
                    <div class="col-xs-6"><strong>{{ totalTaxableOrderOptions | money }}</strong></div>
                </div>
                <div class="row">
                    <div class="col-xs-6 text-right">Non-Taxable Order Options:</div>
                    <div class="col-xs-6"><strong>{{ totalNonTaxableOrderOptions | money }}</strong></div>
                </div>

                <template v-if="paymentType === 'rto'">
                    <div class="row">
                        <div class="col-xs-6 text-right">RTO Order Options:</div>
                        <div class="col-xs-6"><strong>{{ totalRtoOrderOptions | money }}</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">Non-RTO Order Options:</div>
                        <div class="col-xs-6"><strong>{{ totalNonRtoOrderOptions | money }}</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">Added to RTO Deposit:</div>
                        <div class="col-xs-6"><strong>{{ totalRtoDepositOptions | money }}</strong></div>
                    </div>
                </template>
            </div>
        </div>

        <!-- (cash) sales Tax -->
        <div class="list-group-item list-group-item-info lead">
            <div class="row">
                <div class="col-xs-6 text-right">Tax:</div>
                <div class="col-xs-6">
                    <strong>{{ salesTax | money }}</strong>
                    <span class="collapse-badge" @click="toggleDetails('showTaxes')">
                        <span v-if="!expensesTool.showTaxes">Details</span>
                        <span v-else>Hide</span>
                    </span>
                </div>
            </div>

            <div class="small" v-show="expensesTool.showTaxes">
                <div class="row">
                    <div class="col-xs-6 text-right">Tax Rate:</div>
                    <div class="col-xs-6"><strong>{{ salesTaxRate }}%</strong></div>
                </div>
            </div>
        </div>

        <div class="list-group-item list-group-item-warning lead main">
            <div class="row">
                <div class="col-xs-6 text-right">Total Purchase:</div>
                <div class="col-xs-6">
                    <strong>{{ totalPurchase | money }}</strong>
                </div>
            </div>
        </div>

        <div class="list-group-item list-group-item-warning lead main">
            <div class="row">
                <div class="col-xs-6 text-right">Minimum Amount Due On Order:</div>
                <div class="col-xs-6">
                    <strong>{{ minAmountApplyOrder | money }}</strong>
                    <span class="collapse-badge" @click="toggleDetails('showAmountDues')">
                        <span v-if="!expensesTool.showAmountDues">Details</span>
                        <span v-else>Hide</span>
                    </span>
                </div>
            </div>

            <div class="small" v-show="expensesTool.showAmountDues">
                <div class="row">
                    <div class="col-xs-6 text-right">(Manufacturer) Deposit Amount Due On Order:</div>
                    <div class="col-xs-6"><strong>{{ mfDepositAmountDue | money }}</strong></div>
                </div>
                <div class="row" v-if="paymentType === 'rto'">
                    <div class="col-xs-6 text-right">(Rto) Deposit Amount Due On Order:</div>
                    <div class="col-xs-6"><strong>{{ rtoDepositAmountDue | money }}</strong></div>
                </div>
            </div>
        </div>

        <!-- Change Order Fees -->
        <div class="list-group-item list-group-item-info lead" v-if="isChangeOrder">
            <div class="row">
                <div class="col-xs-6 text-right">Change Order Fee:</div>
                <div class="col-xs-6"><strong>{{ changeOrderFee | money }}</strong></div>
            </div>
        </div>

        <div class="list-group-item list-group-item-info lead main">
            <div class="row">
                <div class="col-xs-6 text-right">Amount Received:</div>
                <div class="col-xs-6"><strong>{{ amountReceived | money }}</strong></div>
            </div>
        </div>

        <div class="list-group-item list-group-item-info lead main">
            <div class="row">
                <div class="col-xs-6 text-right">Balance Due:</div>
                <div class="col-xs-6"><strong>{{ balanceDue | money }}</strong></div>
            </div>
        </div>


        <div class="list-group-item text-center">
            <small>Retail price includes delivery and setup (taxes not included)</small>
        </div>

        <!-- Rent-to-Own Opts -->
        <div class="list-group-item list-group-item-warning main">
            <div class="row">
                <div class="col-xs-12 text-center"><strong>Rent-to-Own Options</strong></div>
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">RTO Deposit:</div>
                <div class="col-xs-6"><strong>{{ rtoDeposit | money }}</strong></div>
            </div>

            <div class="row" v-for="(rtoPayment, termId) in rtoPayments">
                <div class="col-xs-6 text-right">{{ termId }}-month payment:</div>
                <div class="col-xs-6"><strong>{{ rtoPayment | money }}</strong></div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapGetters, mapActions} from 'vuex'
// MAKE vuex?
    export default {
        data() {
            return {}
        },
        computed: {
            ...mapGetters({
                expensesTool: 'dealerOrderForm/uiTools/getExpensesTool',
                dealer: 'dealerOrderForm/orderDealer',
                rtoTerms: 'dealerOrderForm/orderTerms/orderRtoTerms',
                rtoType: 'dealerOrderForm/orderRtoType',
                paymentType: 'dealerOrderForm/orderPaymentType',
                amountReceived: 'dealerOrderForm/orderAmountReceived',

                currentBuilding: 'dealerOrderForm/currentBuilding',
                editionBlocked: 'dealerOrderForm/editionBlocked',

                // totalBuildingPrice: 'dealerOrderForm/currentOrderTotalBuildingPrice',
                totalOrderOptions: 'dealerOrderForm/currentTotalOrderOptions',
                totalTaxableOrderOptions: 'dealerOrderForm/currentTotalTaxableOptions',
                totalNonTaxableOrderOptions: 'dealerOrderForm/currentTotalNonTaxableOptions',
                totalRtoOrderOptions: 'dealerOrderForm/currentTotalRtoOptions',
                totalNonRtoOrderOptions: 'dealerOrderForm/currentTotalNonRtoOptions',
                totalRtoDepositOptions: 'dealerOrderForm/currentTotalRtoDepositOptions',

                orderTotal: 'dealerOrderForm/currentOrderTotal',
                totalPurchase: 'dealerOrderForm/currentOrderTotalPurchase',
                totalAmount: 'dealerOrderForm/currentTotalAmount',
                salesTax: 'dealerOrderForm/currentOrderSalesTax',
                salesTaxRate: 'dealerOrderForm/orderSalesTaxRate',
                changeOrderFee: 'dealerOrderForm/currentChangeOrderFee',
                purchaseOutright: 'dealerOrderForm/currentOrderPurchaseOutrightDepositAmount',
                rtoAmount: 'dealerOrderForm/currentOrderRtoAmount',
                rtoPayment: 'dealerOrderForm/currentOrderRtoPayment',
                rtoDeposit: 'dealerOrderForm/currentOrderRtoDeposit',
                minAmountApplyOrder: 'dealerOrderForm/currentOrderMinAmountApplyOrder',
                totalAmountDue: 'dealerOrderForm/currentOrderTotalDepositAmountDue',
                mfDepositAmountDue: 'dealerOrderForm/currentOrderMfDepositAmountDue',
                rtoDepositAmountDue: 'dealerOrderForm/currentOrderRtoDepositAmountDue',
                balanceDue: 'dealerOrderForm/currentBalanceDue',
                isChangeOrder: 'dealerOrderForm/currentOrderIsChangeOrder'
            }),
            rtoPayments() {
                let self = this
                let terms = {
                    24: 0,
                    36: 0,
                    48: 0,
                    60: 0
                }

                if (_.size(self.rtoTerms) === 0) return terms

                let rtoAmount = this.rtoAmount
                _.each(terms, function(value, key) {
                    let rtoAdvanceMRP = rtoAmount / self.rtoTerms[key]['rtoFactor']
                    let rtoSalesTax = rtoAdvanceMRP * (self.salesTaxRate / 100)
                    let rtoTotalAdvanceMRP = rtoAdvanceMRP + rtoSalesTax
                    terms[key] = rtoTotalAdvanceMRP
                })
                return terms
            }
        },
        methods: {
            ...mapActions({
                updateExpensesTool: 'dealerOrderForm/uiTools/updateExpensesTool'
            }),
            toggleDetails(key) {
                this.updateExpensesTool({
                    [key]: !this.expensesTool[key]
                })
            }
        }
    }
</script>

<style type="text/css">

</style>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .collapse-badge {
        cursor: pointer;
        display: inline-block;
        min-width: 10px;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #fff;
        border-radius: 10px;
        border: 1px solid #cacaca;
        float: right;
    }

    .lead {
        font-size: 18px;
    }
</style>