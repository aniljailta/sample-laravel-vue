<template>

    <div class="col-xs-12 plr-none">
        <div class="row">
            <div class="col-md-6">
                <div class="list-group overlayable">
                    <div class="list-group-item item-heading">
                        <div class="col-xs-2 plr-none text-left">
                            <button class="btn btn-default"
                                    v-on:click.prevent="goToStep('previous')"><i class="fa fa-arrow-left fa-fw"></i> Previous
                            </button>
                        </div>
                        <div class="col-xs-8 plr-none text-center">
                            <h4>Submit</h4>
                        </div>
                        <div class="col-xs-2 plr-none text-right">
                            <button class="btn"
                                    v-if="nextStep('next')"
                                    v-bind:class="buttonSuggesting"
                                    v-on:click.prevent="goToStep('next')">Next <i class="fa fa-arrow-right fa-fw"></i>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="list-group-item sub-step">
                        <button type="button" @click.prevent="showCollectDepositModal()" class="btn btn-sm btn-danger">Collect Deposit and Customer Receipt</button>
                        <span v-if="isValidRepositReceived === false">
                            <i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i>
                        </span>

                        <div v-if="$v.amountReceived.$dirty && $v.amountReceived.required === false" class="alert alert-danger text-standard" role="alert">The Amount Received field is required.</div>
                        <div v-if="$v.amountReceived.$dirty && $v.amountReceived.between === false" class="alert alert-danger text-standard" role="alert">The Amount Received must be more or equal the Total Amount Due on Order.</div>
                        <div v-if="$v.paymentMethod.$dirty && $v.paymentMethod.required === false" class="alert alert-danger text-standard" role="alert">The Payment Method field is required.</div>
                        <div v-if="$v.transactionId.$dirty && $v.transactionId.required === false" class="alert alert-danger text-standard" role="alert">The Transaction ID field is required.</div>

                        <template v-if="paymentMethod !== 'credit_card'">
                            <br>
                            <h4 class="list-group-item-heading" style="margin-top: 1em">Upload deposit receipt for funds received</h4>
                            <div class="row">
                                <!-- wrap esignedOrderDocumentsAttachment for reset cache of data and components  -->
                                <deposit-receipt-file v-for="(dra, draIndex) in [depositReceiptAttachment]"
                                                      v-if="dra.files.length > 0 || dra.canGenerate || dra.canUpload"
                                                      :key="dra.key"
                                                      ref="depositReceiptFile"
                                                      class="col-xs-12 col-sm-6 deposti-receipt__compenent"
                                                      :attachment="dra"
                                                      :isValid="!$v.attachedCategories.signedDepositReceipt.$error"
                                                      :storableId="orderCurrent.id">
                                </deposit-receipt-file>
                                <div class="col-xs-12">
                                    <em>
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        Acceptable documents for deposit receipt of funds received include:
                                        bank deposit receipt, screenshot of credit card transaction and photos of checks.
                                        Dealers are responsible for all funds received from customer until deposited.
                                    </em>
                                </div>

                                <div class="col-xs-12" v-if="fileCategories">
                                    <div v-if="$v.attachedCategories.signedDepositReceipt && $v.attachedCategories.signedDepositReceipt.$dirty && $v.attachedCategories.signedDepositReceipt.accepted === false"
                                         class="alert alert-danger"
                                         role="alert">
                                        You need to attach a 'Deposit receipt for funds received.
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- This field is not used with store (avoid re-hashing) -->
                    <div class="list-group-item sub-step">
                        <h4 class="list-group-item-heading" style="margin-top: 1em">Notes to Admin:</h4>
                        <textarea type="text"
                                  :disabled="editionBlocked"
                                  class="form-control"
                                  id="note-dealer"
                                  placeholder="Notes"
                                  :value="noteDealer"
                                  @input="noteDealer = $event.target.value"/>
                    </div>
                    <div class="list-group-item sub-step preloader-container text-center"
                         v-if="cancellationNotRequested()">
                        <data-process :process="dataProcess" :with_loader="true" :with_icon="true">
                            <div slot="success" class="lead" v-if="dataProcess.success">
                                <p>{{ dataProcess.success }}</p>
                                <p>Date: <strong>{{ orderCurrent.updatedAt }}</strong></p>
                            </div>
                        </data-process>
                        <button type="button" class="btn btn-lg btn-success" @click="submit"
                                :disabled="dataProcess.running || (editionBlocked && orderCurrent.statusId !== 'signed')">Submit Order</button>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-sm-6">
                <building-summary></building-summary>
            </div>
        </div>

    </div>

</template>

<script type="text/babel">
    /*global swal*/
    import {mapActions, mapGetters} from 'vuex'
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import submitStepValidation from 'src/validations/dealer-order-form/submit-step.validation'
    import DepositReceiptFile from './submit-step/DepositReceiptFile.vue'
    import BuildingSummary from './submit-step/BuildingSummary.vue'

    export default {
        extends: baseDataItem,
        mixins: [vuelidateAnyerror],
        data() {
            return {
                noteDealer: null,
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        components: {DepositReceiptFile, BuildingSummary},
        validations: submitStepValidation,
        created() {
            console.log(this.threedViewer)
            this.$watch('$anyerror', (value) => {
                this.updateOrderValidation({'submit': !value})
            })
            this.$watch('orderCurrent.id', (id, oldId) => {
                this.$emit('data-process-reset')
                this.noteDealer = this.orderCurrent.noteDealer
            })
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderStateMode: 'dealerOrderForm/orderStateMode',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                orderOrder: 'dealerOrderForm/orderOrder',
                isValidRepositReceived: 'dealerOrderForm/collectDeposit/isValid',
                depositReceiptAttachment: 'dealerOrderForm/depositReceiptAttachment',
                attachedCategories: 'dealerOrderForm/attachedCategories',
                fileCategories: 'dealerOrderForm/files/categories',
                // for validation
                paymentMethod: 'dealerOrderForm/orderPaymentMethod',
                minAmountApplyToOrder: 'dealerOrderForm/currentOrderMinAmountApplyOrder',
                amountReceived: 'dealerOrderForm/orderAmountReceived',
                transactionId: 'dealerOrderForm/orderTransactionId',
                editionBlocked: 'dealerOrderForm/editionBlocked',
                isChangeOrder: 'dealerOrderForm/currentOrderIsChangeOrder',
                threedViewer: 'dealerOrderForm/order3dViewer'

            }),
            buttonSuggesting() {
                if (!this.nextStep('next') && this.$v.$dirty && !this.$anyerror) {
                    return {'btn-success': true}
                }

                return {'btn-default': true}
            }
        },
        methods: {
            ...mapActions({
                setOrderState: 'dealerOrderForm/setOrderState',
                showCollectDepositModal: 'dealerOrderForm/collectDeposit/showModal',
                updateOrderValidation: 'dealerOrderForm/updateOrderValidation',
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder',
                updateOrderSummary: 'dealerOrderForm/updateOrderSummary'
            }),
            cancellationNotRequested () {
                return typeof this.orderCurrent.status !== 'undefined' && this.orderCurrent.status.id !== 'cancellation_requested'
            },
            change(object) {
                this.updateOrderOrder(object)

                _.each(object, (val, key) => {
                    let $v = _.get(this.$v, key, false)
                    if ($v) $v.$touch()
                })
            },
            $validate(steps, options) {
                let isValid = true
                if (options.$reset) this.$v.$reset()
                if (options.$touch) this.$v.$touch()
                if (this.$anyerror) isValid = !this.$anyerror
                // if (this.$v.$error) isValid = !this.$v.$error

                this.updateOrderValidation({'submit': isValid})
                return isValid
            },
            changeMode(mode) {
                this.$emit('data-process-update', {success: null})
                this.setOrderState({ mode: mode })
            },
            submit() {
                let self = this

                this.setOrderState({ mode: 'submit' })
                this.$emit('submit-order', {
                    item: {
                        statusId: 'submitted',
                        id: this.orderCurrent.id,
                        noteDealer: this.noteDealer
                    },
                    beforeCb() {
                        self.run({text: 'Submitting..'})
                    },
                    successCb(response) {
                        self.$emit('data-process-update', {running: false})

                        swal({
                            title: 'Success',
                            text: 'The order has been successfully submitted.',
                            type: 'success',
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Close'
                        })
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            },
            goToStep(direction) {
                this.$emit('go-to-step', {step: direction})
            },
            nextStep(direction) {
                return this.$parent.nextStep(direction)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    .order-state {
        margin-top: -1px;
        background: #f5f5f5;

        .panel-body {
            padding-top: 0.5em;
        }
    }

    .panel {
        border-radius: 0px;
    }
    .panel-heading {
        border-radius: 0px;
    }
    .panel-body {
        padding: 0px;
    }

    .deposit-receipt__component {
        float: none;
        margin: 0 auto;
    }
</style>