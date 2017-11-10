<template>

    <!-- Order Info -->
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
                        <h4>Order Options</h4>
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

                    <h4 class="list-group-item-heading" v-if="orderOptionsAvailable">Need to add additional purchases to the order?</h4>
                    <order-options-modal ref="orderOptionsModal"></order-options-modal>
                    <button type="button" class="btn btn-danger"
                            v-on:click.prevent="openOrderOptionsModal"
                            v-if="orderOptionsAvailable">
                        Edit Order Options
                    </button>

                    <h4 class="list-group-item-heading">Sales Tax Rate (Override):</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">%</span>
                                <input @change="change({'salesTaxRate': parseFloat($event.target.value)})"
                                       :disabled="editionBlocked"
                                       type="number"
                                       class="form-control"
                                       v-bind:value="salesTaxRate">
                            </div>
                        </div>
                    </div>

                    <!-- [Delivery Remarks] -->
                    <h4 class="list-group-item-heading">Delivery Remarks:</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.levelPad"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.levelPad': $event.target.checked})">
                                    Level Pad
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.softWhenWet"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.softWhenWet': $event.target.checked})">
                                    Soft when wet
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.widthRestrictions"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.widthRestrictions': $event.target.checked})">
                                    Width Restrictions
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.heightRestrictions"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.heightRestrictions': $event.target.checked})">
                                    Height Restrictions
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.mustCrossNeighboringProperty"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.mustCrossNeighboringProperty': $event.target.checked})">
                                    Must cross neighboring property
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" :value="true" :checked="deliveryRemarks.requiresSiteVisit"
                                           :disabled="editionBlocked"
                                           @change="change({'deliveryRemarks.requiresSiteVisit': $event.target.checked})">
                                    Requires site visit
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="list-group-item-heading">Notes:</h4>
                            <textarea class="form-control"
                                      :disabled="editionBlocked"
                                      placeholder="Enter any special notes with respect to the building or delivery here."
                                      :value="deliveryRemarks.notes"
                                      @change="change({'deliveryRemarks.notes': $event.target.value})"></textarea>
                        </div>
                    </div>
                    <!-- [Delivery Remarks] -->

                    <!-- [Order Date] -->
                    <h4 class="list-group-item-heading">Order date (MM/DD/YYYY):</h4>
                    <Cleave v-show="!editionBlocked"
                            :options='cleaveOptionsDate'
                            ref="dateExpected"
                            class="form-data"
                            :placeholder="date.placeholder"
                            :value="orderDate"
                            @input="updateOrder($event)">
                    </Cleave>
                    <order-datepicker ref="dateExpected" v-show="false"
                                      :format="date.format"
                                      :placeholder="date.placeholder"
                                      :value="orderDate">
                    </order-datepicker>
                    <div class="row" v-show="editionBlocked">
                        <div class="form-group col-md-6">
                            <input type="text"
                                   disabled="disabled"
                                   class="form-control"
                                   v-bind:value="orderDate">
                        </div>
                    </div>

                    <div v-if="$v.orderDate.$dirty && $v.orderDate.date === false" class="alert alert-danger"
                         role="alert">Enter a valid Order date (MM-DD-YYYY)
                    </div>
                    <div v-if="$v.orderDate.$dirty && $v.orderDate.required === false"
                         class="alert alert-danger" role="alert">This field is required.
                    </div>

                    <h4 class="list-group-item-heading" style="margin-top: 1em">Estimated Delivery Period:</h4>
                    <div class="row">
                        <div class="col-md-12 form-inline">
                            <div class="form-group">
                                <label>From:</label>
                                <input type="text"
                                       disabled="disabled"
                                       class="form-control"
                                       v-bind:value="currentOrderCustomerExpectsDate.start">
                            </div>
                            <div class="form-group">
                                <label>To:</label>
                                <input type="text"
                                       disabled="disabled"
                                       class="form-control"
                                       v-bind:value="currentOrderCustomerExpectsDate.end">
                            </div>
                        </div>
                    </div>
                    <!-- [/Order Date] -->
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
    import orderStageValidation from 'src/validations/dealer-order-form/order-stage.validation.js'
    import OrderDatepicker from './OrderDatepicker.vue'
    import ExpenseDetails from '../ExpenseDetails.vue'
    import OrderOptionsModal from './order-options/OrderOptionsModal.vue'
    import Cleave from 'vue-cleave/src/Cleave.vue'
    import 'cleave.js/dist/addons/cleave-phone.mx'

    export default {
        name: 'order-stage',
        mixins: [vuealidateAnyerror],
        components: {
            OrderDatepicker,
            ExpenseDetails,
            OrderOptionsModal,
            Cleave
        },
        validations: orderStageValidation,
        data: function () {
            return {
                total: 0,
                date: {
                    placeholder: 'MM/DD/YYYY',
                    format: 'MM/dd/yyyy'
                },
                cleaveOptionsDate: {
                    date: true,
                    datePattern: ['m', 'd', 'Y']
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
            deliveryRemarksSummary() {
                var total = _.size(this.deliveryRemarks)
                var selectedEls = _.filter(this.deliveryRemarks, function (el) {
                    return !(el === false || el === null || el === '')
                })

                var selected = _.size(selectedEls)
                return selected + '/' + total
            },
            buttonSuggesting() {
                return {'btn-default': true}
            },
            orderOptionsAvailable() {
                let allowedStatuses = ['draft', 'review_needed']

                return allowedStatuses.indexOf(this.orderCurrent.statusId) !== -1 || _.isEmpty(this.orderCurrent)
            },
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                dealer: 'dealerOrderForm/orderDealer',
                building: 'dealerOrderForm/orderBuilding',
                order: 'dealerOrderForm/orderOrder',
                deliveryRemarks: 'dealerOrderForm/orderDeliveryRemarks',
                orderDate: 'dealerOrderForm/orderDate',
                salesTaxRate: 'dealerOrderForm/orderSalesTaxRate',

                currentOrderCustomerExpectsDate: 'dealerOrderForm/currentOrderCustomerExpectsDate',
                editionBlocked: 'dealerOrderForm/editionBlocked',
                orderOptions: 'dealerOrderForm/options/orderOptionsList'
            })
        },
        methods: {
            ...mapActions({
                getOrderRtoTerms: 'dealerOrderForm/orderTerms/getOrderRtoTerms',
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder'
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
            updateOrder(val) {
                this.updateOrderOrder({ date: val.replace(/^(\d{2})(\d{2})/, '$1/$2/') })
            },
            openOrderOptionsModal() {
                this.$refs.orderOptionsModal.$emit('open-modal')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    span.badge.notice {
        color: red;
        background: rgb(218, 218, 218);
    }

    h4.list-group-item-heading {
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .datepicker {
        float: none;
    }

    .form-group {
        margin-bottom: 15px!important;
    }

    .form-data {
        border-radius: 4px;
        background-image: none;
        border: 1px solid #ababab;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
    }
</style>