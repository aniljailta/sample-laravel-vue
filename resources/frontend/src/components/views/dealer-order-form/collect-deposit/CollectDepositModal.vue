<template>

    <modal v-bind:show="true"
           v-bind:modal-class="modalClass"
           v-bind:modal-style="modalStyle"
           v-bind:mask-style="maskStyle">

        <div>
            <div class="panel-heading">
                <h2 class="panel-title">Collect Deposit</h2>
            </div>
            <div class="panel-body modal-body">
                <div class="form-container">
                    <collect-deposit-form ref="collectDepositForm"
                                     :payment-token="paymentToken"
                                     @complete-payment="completePayment">
                    </collect-deposit-form>
                </div>
            </div>
            <div class="panel-footer" style="text-align: center">
                <button type="button" class="btn btn-default" v-on:click="close">Close</button>
            </div>
        </div>

    </modal>

</template>

<script type="text/babel">
    import {mapActions} from 'vuex'
    import Modal from 'src/components/ui/Modal.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import CollectDepositForm from './CollectDepositForm.vue'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                modalTitle: '',
                modalClass: 'col-md-4 col-sm-7 col-xs-11',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                },
                paymentToken: null
            }
        },
        components: {
            Modal,
            CollectDepositForm
        },
        methods: {
            ...mapActions({
                hideCollectDepositModal: 'dealerOrderForm/collectDeposit/hideModal'
            }),
            close() {
                this.hideCollectDepositModal()
            },
            completePayment(token) {
                this.paymentToken = token
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>