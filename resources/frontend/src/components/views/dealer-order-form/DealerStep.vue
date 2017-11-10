<template>

        <div class="col-xs-12 panel panel-default">
                <div class="row">
                    <h4></h4>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="business-name">Dealer selection *</label>
                            <select class="form-control"
                                    v-bind:class="{ 'invalid': $v.dealer.id.$error }"
                                    name="Dealership"
                                    v-bind:value="dealer.id"
                                    @blur="$v.dealer.id.$touch"
                                    @change.prevent="changeDealer($event.target.value)">
                                <option value="" selected="selected" disabled>Select...</option>
                                <option v-for="(dealer, dealer_id) in dealers" v-bind:value="dealer_id">{{ dealer.businessName }}</option>
                            </select>
                            <div v-if="$v.dealer.id.$dirty && !$v.dealer.id.required" class="alert alert-danger">This field is required.</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="sales-person">Sales Person *</label>
                            <input type="text"
                                   class="form-control"
                                   v-bind:class="{ 'invalid': $v.dealer.salesPerson.$error }"
                                   name="Sales Person"
                                   :disabled="editionBlocked"
                                   id="sales-person"
                                   placeholder="Sales Person"
                                   v-bind:value="dealer.salesPerson"
                                   @blur="$v.dealer.salesPerson.$touch"
                                   @input="updateOrderDealer({'salesPerson': $event.target.value})">
                            <div v-if="$v.dealer.salesPerson.$dirty && !$v.dealer.salesPerson.required" class="alert alert-danger">This field is required.</div>
                            <div v-if="$v.dealer.salesPerson.$dirty && !$v.dealer.salesPerson.name" class="alert alert-danger">Enter a valid Sales Person.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="business-name">Business Name</label>
                            <input type="text" class="form-control" id="business-name" placeholder="Business Name" v-bind:value="dealer.businessName" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="business-name">Sales Tax Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="tax-rate" placeholder="Sales Tax Rate" v-bind:value="dealer.taxRate" disabled>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="business-address">Address</label>
                            <input type="text" class="form-control" id="business-address" placeholder="Address" v-bind:value="dealer.businessAddress" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="business-phone">Phone</label>
                            <input type="text" class="form-control" id="business-phone" placeholder="Phone Number" v-bind:value="dealer.phoneNumber" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="business-email">Email</label>
                            <input type="text" class="form-control" id="business-email" placeholder="Email address" v-bind:value="dealer.email" disabled>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <button class="btn btn-default"
                            style="margin-bottom: 0.5em"
                            @click.prevent="nextStep()">Next<i class="fa fa-arrow-right fa-fw"></i>
                    </button>
                </div>

        </div>

</template>

<script type="text/babel">
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import dealerStepValidation from 'src/validations/dealer-order-form/dealer-step.validation.js'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        name: 'dealer-step',
        mixins: [vuelidateAnyerror],
        validations: dealerStepValidation,
        data() {
            return {
                cleaveOptions: {
                    numeral: true,
                    numeralDecimalScale: 4
                }
            }
        },
        created() {
            let self = this
            this.getAllDealers({
                query: {
                    include: ['location'],
                    where: {
                        is_active: 'yes'
                    },
                    per_page: 9999
                }
            }).then(response => {
                self.$bus.$emit('enable-form')
            })
            this.$watch('$anyerror', (value) => {
                this.updateOrderValidation({'dealer': !value})
            })
        },
        computed: {
            ...mapGetters({
                dealer: 'dealerOrderForm/orderDealer',
                allDealers: 'dealerOrderForm/dealers/dealers',
                editionBlocked: 'dealerOrderForm/editionBlocked'
            }),
            dealers() {
                if (this.allDealers.length <= 0) return {}
                return _.keyBy(this.allDealers, 'id')
            }
        },
        methods: {
            ...mapActions({
                getAllDealers: 'dealerOrderForm/dealers/getAllDealers',
                updateOrderDealer: 'dealerOrderForm/updateOrderDealer',
                updateOrderValidation: 'dealerOrderForm/updateOrderValidation',
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder'
            }),
            changeDealer(dealerID) {
                if (dealerID !== '') {
                    let dealer = (typeof this.dealers[dealerID] !== 'undefined') ? this.dealers[dealerID] : null
                    if (dealer) {
                        this.updateOrderDealer({
                            id: dealer.id,
                            businessName: dealer.businessName,
                            businessAddress: dealer.location.address,
                            phoneNumber: dealer.phone,
                            email: dealer.email,
                            taxRate: dealer.taxRate,
                            depositType: dealer.depositType,
                            cashSaleDepositRate: dealer.cashSaleDepositRate,
                            salesPerson: null
                        })

                        // override sales tax rate by dealer tax rate
                        this.updateOrderOrder({
                            salesTaxRate: dealer.taxRate
                        })
                    } else {
                        this.updateOrderDealer({
                            id: null,
                            businessName: null,
                            businessAddress: null,
                            phoneNumber: null,
                            email: null,
                            taxRate: null,
                            depositType: null,
                            cashSaleDepositRate: null,
                            salesPerson: null
                        })
                    }
                }
            },
            nextStep() {
                this.$emit('go-to-step', {step: 'next'})
            },
            $validate(steps, options) {
                if (options.$reset) this.$v.$reset()
                if (options.$touch) this.$v.$touch()
                return !this.$anyerror
            }
        }
    }
</script>

<style type="text/css">
    .form-group {
        margin-bottom: 15px!important;
    }
</style>