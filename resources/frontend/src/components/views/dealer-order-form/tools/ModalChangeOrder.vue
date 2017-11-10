<template>

    <div>
        <modal :show="show"
               :modal-class="modalClass"
               :modal-style="modalStyle"
               :mask-style="maskStyle">

            <div>
                <div class="panel-heading">
                    <h4 class="panel-title">Change Order</h4>
                </div>
                <div class="panel-body modal-body overlayable">
                    <div class="">
                        <data-process :with_loader="true" :process="dataProcess"></data-process>
                        <div v-if="!(dataProcess.running || dataProcess.success)" class="modalContainer">
                            <div class="text-center">
                                <span>Additional fees need to be collected from the customer when creating a Change Order and
                                    should only be used if the customer is requesting changes to the building.
                                    If you need to make changes to the order unrelated to the building,
                                    please contact {{ orderDealer.businessName }} instead.
                                    Would you like to continue with the Change Order?</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <button type="button"
                            class="btn btn-default ansno"
                            v-show="!dataProcess.success"
                            :disabled="dataProcess.running"
                            @click="hideChangeOrderModal">
                        No
                    </button>
                    <button type="button"
                            class="btn btn-default"
                            v-show="dataProcess.success"
                            :disabled="dataProcess.running"
                            @click="hideChangeOrderModal">
                        Close
                    </button>
                    <button type="button"
                            class="btn btn-primary"
                            :disabled="dataProcess.running"
                            v-show="!dataProcess.success"
                            @click="changeOrder(orderCurrent.id)">
                        Yes
                    </button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        extends: baseDataItem,
        data() {
            return {
                modalClass: 'modal-block-primary col-md-6',
                modalStyle: {
                    float: 'none',
                    padding: '0',
                    display: 'inline-block'
                },
                maskStyle: {
                    position: 'fixed'
                },
                dataProcess: {
                    type: 'form',
                    running: false,
                    text: 'Creating a change order...'
                }
            }
        },
        components: {
            Modal
        },
        props: {
            show: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            ...mapActions({
                updateOrderSync: 'dealerOrderForm/updateOrderSync',
                cloneOrder: 'dealerOrderForm/cloneOrder',
                hideChangeOrderModal: 'dealerOrderForm/uiTools/hideChangeOrderModal',
                setStateChangeOrderModal: 'dealerOrderForm/uiTools/setStateChangeOrderModal'
            }),
            clear() {
                this.$emit('data-process-update', {
                    error: null,
                    success: null
                })
            },
            close() {
                this.clear()
                this.$emit('close')
            },
            changeOrder(id) {
                let self = this
                self.cloneOrder({
                    payload: {
                        order_uuid: id
                    },
                    beforeCb() {
                        self.run({text: 'Creating a change order...', type: 'data'})
                        self.setStateChangeOrderModal('idle')
                    },
                    successCb(response) {
                        self.$nextTick(function () {
                            self.$emit('data-process-update', {
                                running: false,
                                success: 'Change order successfully created.'
                            })
                            self.updateOrderSync({merging: 'done'})
                            self.$bus.$emit('dofOrderLoaded', { step: 'building' })
                        })
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            }
        },
        computed: {
            running() {
                return this.process.running
            },
            ...mapGetters({
                orderDealer: 'dealerOrderForm/orderDealer',
                orderDealerID: 'dealerOrderForm/orderDealerID',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                changeableOrder: 'dealerOrderForm/changeableOrder'
            })
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .modal-container .panel-footer{margin:0}
    .ansno{margin-right: 5px}
    .modalContainer{
        display: flex;
        align-items: center;
    }
    .panel-footer button.btn{
        line-height: 14px;
        padding: 9px 12px;
    }
</style>