<template>
    <div>
        <div class="panel-heading">
            <h2 class="panel-title">Cancellation Request Form</h2>
        </div>

        <div class="panel-body overlayable">
            <div class="form-container">
                <data-process :process="dataProcess" :with_loader="true"></data-process>

                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <div class="row" v-if="!inputFormHidden">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <p class="text-center">
                                            Please indicate the reason that the customer would like to cancel the order. <span class="orange-color">Cancellation fees may apply.</span></p>
                                        <textarea class="form-control"
                                              id="form-dealer-notes" :class="{'invalid': $v.noteDealer.$error}"
                                              @input="$v.noteDealer.$touch"
                                              @blur="$v.noteDealer.$touch"
                                              v-model="noteDealer"></textarea>
                                        <div v-if="$v.noteDealer.$error" class="alert alert-danger" role="alert">
                                            The Notes field is required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="align-center">
                <button type="button" class="btn btn-default" @click="uiToolsHideCancellationRequestForm">Close</button>
                <button type="button" class="btn btn-primary" @click="submit" :disabled="dataProcess.running || inputFormHidden">Confirm
                </button>
            </div>
        </div>

    </div>

</template>

<script type="text/babel">
    import cancellationRequestFormValidation from 'src/validations/dealer-order-form/cancellation-request-form-tools.validation.js'
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        extends: baseDataItem,
        components: {
            baseDataItem
        },
        data() {
            return {
                inputFormHidden: false,
                noteDealer: null,
                statusId: null,
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        validations: cancellationRequestFormValidation,
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderDealerID: 'dealerOrderForm/orderDealerID',
                getUiToolsStateLoadForm: 'dealerOrderForm/uiTools/getUiToolsStateLoadForm'
            })
        },
        methods: {
            ...mapActions({
                setOrderState: 'dealerOrderForm/setOrderState',
                uiToolsHideCancellationRequestForm: 'dealerOrderForm/uiTools/uiToolsHideCancellationRequestForm',
                uiToolsSetStateCancellationRequestForm: 'dealerOrderForm/uiTools/uiToolsSetStateCancellationRequestForm',
                saveDealerOrder: 'dealerOrderForm/saveDealerOrder'
            }),
            submit() {
                this.$v.$touch()
                if (this.$v.$error) return

                let self = this

                self.statusId = 'cancellation_requested'

                // before
                self.run({text: 'Saving..', type: 'form'})
                self.uiToolsSetStateCancellationRequestForm({state: 'idle'})

                // save
                self.saveDealerOrder({
                    saveAs: self.saveAs,
                    withEmpty: true,
                    noteDealer: self.noteDealer,
                    statusId: self.statusId
                }).then(response => {
                    self.saveAs = 'existing'
                    self.$emit('data-process-update', {
                        running: false,
                        success: 'The cancellation request has been successfully submitted. You will be notified once it has been processed.'
                    })
                    self.inputFormHidden = true
                }).catch(response => {
                    self.$emit('data-failed', response)
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .panel-body {
        position: relative;
    }

    textarea {
        resize: vertical;
    }

    .align-center {
        text-align: center;
    }

    .orange-color {
        color: orangered;
    }
</style>