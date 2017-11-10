<template>

    <!-- Purchase Method -->
    <div class="row">
        <div class="col-md-7">
            <div class="list-group">
                <div class="list-group-item item-heading">
                    <div class="col-xs-2 plr-none text-left">
                        <button class="btn btn-default"
                                v-on:click.prevent="goToStep('previous')"><i class="fa fa-arrow-left fa-fw"></i> Previous
                        </button>
                    </div>
                    <div class="col-xs-8 plr-none text-center">
                        <h4>Purchase Method</h4>
                    </div>
                    <div class="col-xs-2 plr-none text-right">
                        <button class="btn"
                                v-bind:class="buttonSuggesting"
                                v-on:click.prevent="goToStep('next')">Next <i class="fa fa-arrow-right fa-fw"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="list-group-item sub-step">

                    <!-- [Payment Type] -->
                    <h4 class="list-group-item-heading">Please choose payment type:</h4>
                    <div class="btn-group btn-group-lg1" data-toggle="buttons">
                        <label :class="{'active': paymentType === 'cash'}" class="btn btn-default" :disabled="editionBlocked">
                            <input @click="change({'paymentType': $event.target.value})"
                                   :disabled="editionBlocked"
                                   type="radio"
                                   name="paymentType"
                                   :value="'cash'"
                                   :checked="paymentType === 'cash'">
                            <i class="fa fa-money"></i> Cash
                        </label>
                        <label :class="{'active': paymentType === 'rto'}" class="btn btn-default" :disabled="editionBlocked">
                            <input @click="change({'paymentType': $event.target.value})"
                                   :disabled="editionBlocked"
                                   type="radio"
                                   name="paymentType"
                                   :value="'rto'"
                                   :checked="paymentType === 'rto'">
                            <i class="fa fa-history"></i> Rent-to-Own
                        </label>
                    </div>
                    <div v-if="$v.paymentType.$dirty && $v.paymentType.required === false" class="alert alert-danger" role="alert">The type of payment must be indicated.</div>
                    <!-- [Payment Type] -->

                </div>

                <!-- [Promo 99] -->
                <div class="list-group-item sub-step" v-if="paymentType === 'rto'">
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <label>
                                    <input type="checkbox"
                                           @change="change({'promo99': $event.target.checked})"
                                           :value="true"
                                           :checked="promo99">
                                    $99 promo sale
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- [/Promo 99] -->

                    <!-- [RTO Term] -->
                    <h4 class="list-group-item-heading">Please choose your terms:</h4>
                    <div class="btn-group" data-toggle="buttons">
                        <label v-for="term in rtoTerms" :class="{'active': rtoTerm && rtoTerm == term.value}" class="btn btn-default">
                            <input @click="change({'rtoTerm': $event.target.value})"
                                   type="radio"
                                   name="rtoTerm"
                                   :value="term.value"
                                   :checked="rtoTerm && rtoTerm == term.value">
                            {{ term.name }}
                        </label>
                    </div>
                    <div v-if="$v.rtoTerm.$dirty && $v.rtoTerm.required === false" class="alert alert-danger" role="alert">Rent-to-own terms are required.</div>
                    <!-- [/RTO Term] -->

                </div>
                <div class="list-group-item sub-step" v-if="(paymentType === 'rto' && rtoTerm !== null) || paymentType === 'cash'">
                    <!-- [Amount Received] -->
                    <h4 class="list-group-item-heading">Enter the amount the customer would like to apply to order <br> (minimum required is $ {{ filters.currency(minAmountApplyToOrder) }}):</h4>
                    <div class="btn-group">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input @change="change({'amountReceived': filters.currency($event.target.value)})"
                                   type="number"
                                   :class="{'invalid': $v.amountReceived.$error}" class="form-control"
                                   :value="amountReceived | currency"/>
                        </div>
                    </div>
                    <div v-if="$v.amountReceived.$dirty && $v.amountReceived.between === false" class="alert alert-danger" role="alert">The minimum amount is $ {{ filters.currency(minAmountApplyToOrder) }}</div>
                    <div v-if="$v.amountReceived.$dirty && $v.amountReceived.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                    <!-- [/Amount Received] -->
                </div>
                <div class="list-group-item text-left" v-if="paymentType !== null">
                    <button type="button" @click.prevent="showCollectDepositModal" class="btn btn-danger"
                            :disabled="editionBlocked">Collect Deposit</button>
                </div>

                <div class="list-group-item visible-xs visible-sm">
                    <div class="col-xs-6 plr-none text-left">
                        <button class="btn btn-default"
                                v-on:click.prevent="goToStep('previous')"><i class="fa fa-arrow-left fa-fw"></i> Previous
                        </button>
                    </div>
                    <div class="col-xs-6 plr-none text-right">
                        <button class="btn"
                                v-bind:class="buttonSuggesting"
                                v-on:click.prevent="goToStep('next')">Next <i class="fa fa-arrow-right fa-fw"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <h4>Order Summary</h4>

            <div class="list-group">

                <expense-details ref="expenseDetails"></expense-details>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import vuealidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import purchaseMethodStageValidation from 'src/validations/dealer-order-form/purchase-method-stage.validation.js'
    import OrderDatepicker from './OrderDatepicker.vue'
    import ExpenseDetails from '../ExpenseDetails.vue'

    export default {
        name: 'purchase-method-stage',
        mixins: [vuealidateAnyerror],
        components: {OrderDatepicker, ExpenseDetails},
        validations: purchaseMethodStageValidation,
        data: function () {
            return {
                total: 0,
                date: {
                    placeholder: 'MM/DD/YYYY',
                    format: 'MM/dd/yyyy'
                }
            }
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$parent.revalidate()
            })

            let self = this
            this.getOrderRtoTerms({
                successCb() {
                    self.$bus.$emit('enable-form')
                }
            })
        },
        computed: {
            dataSync() {
                return this.orderState.sync.merging
            },
            buttonSuggesting() {
                if (!this.nextStep('next') && this.$v.$dirty && !this.$anyerror) {
                    return {'btn-success': true}
                }

                return {'btn-default': true}
            },
            deliveryRemarksSummary() {
                var total = _.size(this.deliveryRemarks)
                var selectedEls = _.filter(this.deliveryRemarks, function (el) {
                    return !(el === false || el === null || el === '')
                })

                var selected = _.size(selectedEls)
                return selected + '/' + total
            },
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                dealer: 'dealerOrderForm/orderDealer',
                building: 'dealerOrderForm/orderBuilding',
                rtoTerms: 'dealerOrderForm/orderTerms/orderRtoTerms',

                type: 'dealerOrderForm/orderType',
                paymentType: 'dealerOrderForm/orderPaymentType',
                grossBuydown: 'dealerOrderForm/orderGrossBuydown',
                deliveryRemarks: 'dealerOrderForm/orderDeliveryRemarks',
                orderDate: 'dealerOrderForm/orderDate',
                rtoType: 'dealerOrderForm/orderRtoType',
                rtoTerm: 'dealerOrderForm/orderRtoTerm',
                promo99: 'dealerOrderForm/orderPromo99',

                currentType: 'dealerOrderForm/currentOrderType',
                currentPaymentType: 'dealerOrderForm/currentOrderPaymentType',
                currentRtoType: 'dealerOrderForm/currentOrderRtoType',
                currentRtoTerm: 'dealerOrderForm/currentOrderRtoTerm',
                currentOrderCustomerExpectsDate: 'dealerOrderForm/currentOrderCustomerExpectsDate',

                // for validation
                rtoDeposit: 'dealerOrderForm/currentOrderRtoDeposit',
                minAmountApplyToOrder: 'dealerOrderForm/currentOrderMinAmountApplyOrder',
                minRtoDepositAmount: 'dealerOrderForm/currentOrderMinRtoDepositAmount',
                amountReceived: 'dealerOrderForm/orderAmountReceived',

                editionBlocked: 'dealerOrderForm/editionBlocked'
            })
        },
        methods: {
            ...mapActions({
                getOrderRtoTerms: 'dealerOrderForm/orderTerms/getOrderRtoTerms',
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder',
                showCollectDepositModal: 'dealerOrderForm/collectDeposit/showModal'
            }),
            change(object) {
                this.updateOrderOrder(object)

                _.each(object, (val, key) => {
                    let $v = _.get(this.$v, key, false)
                    if ($v) $v.$touch()
                })
            },
            goToStep(direction) {
                return this.$parent.goToStep(direction)
            },
            nextStep(direction) {
                return this.$parent.nextStep(direction)
            }
        },
        watch: {
            type(newStep, oldStep) {
                if (this.dataSync === 'running') return false
                if (newStep === oldStep) return false

                this.updateOrderOrder({
                    paymentType: null,
                    rtoType: null,
                    rtoTerm: null,
                    promo99: false,
                    grossBuydown: 0,
                    amountReceived: null,
                    generatedFile: null,
                    fileGenerated: null
                })
            },
            paymentType(paymentType, oldPaymentType) {
                if (this.dataSync === 'running') return false
                if (paymentType === oldPaymentType) return false

                if (paymentType === 'cash') {
                    // set RTO options to null
                    this.updateOrderOrder({
                        rtoType: null,
                        rtoTerm: null,
                        promo99: false,
                        grossBuydown: 0
                    })
                }
                if (paymentType === 'rto') {}
                if (paymentType !== oldPaymentType) {
                    this.$nextTick(() => {
                        this.$parent.revalidate()
                    })
                }
            },
            rtoType(rtoType, oldRtoType) {
                if (this.dataSync === 'running') return false
                if (rtoType === oldRtoType) return false

                if (oldRtoType !== null) {
                    if (oldRtoType === 'no-buydown' && rtoType === 'buydown') {
                        this.change({grossBuydown: 0})
                    }
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    span.badge.notice {
        color: red;
        background: rgb(218, 218, 218);
    }
</style>