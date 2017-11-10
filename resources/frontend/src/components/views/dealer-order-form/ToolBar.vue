<template>

    <div>
        <navbar placement="default" type="default">
            <a slot="brand" title="Order Form for Dealers" class="navbar-brand">Order Form for Dealers</a>

            <li>
                <form class="navbar-form navbar-left">
                    <button type="button" class="btn btn-default" v-on:click="uiToolsShowSaveForm" v-bind:disabled="!orderDealerID" @click="changeSubmitMode('draft')">Save</button>
                    <button type="button" class="btn btn-default" v-on:click="uiToolsShowLoadForm" v-bind:disabled="!orderDealerID" @click="changeSubmitMode('draft')">Load</button>
                    <download-document-button :id="orderCurrent.id"
                                              :categoryId="'quote_forms'"
                                              :downloadLabel="'Download Quote'"/>
                </form>
            </li>

                <dropdown>
                    <span slot="button"><i aria-hidden="true" class="fa fa-file-pdf-o"></i> Documents</span>

                    <ul slot="dropdown-menu" class="dropdown-menu">
                        <li><a href="/documents/price-list" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print Current Price List</a></li>
                        <li v-bind:class="{ 'disabled' : !orderDealerID }">
                            <a v-on:click="openDocument('order-form')" target="_blank">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print Current Order Form <small v-show="!orderDealerID" style="color: red">(dealer required)</small>
                            </a>
                        </li>
                        <li><a href="/documents/building-configuration" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Building Configuration</a></li>
                        <li><a href="/documents/customer-delivery-form" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Customer Delivery Form</a></li>
                        <li><a href="/documents/neighbor-release" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Neighbor release form</a></li>
                        <li><a href="/documents/rto-docs" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print Current RTO Documents</a></li>
                        <li><a href="/dealer-form/payment_calculator.pdf" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Payment Calculator</a></li>
                        <li><a href="/documents/promo99" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print Promo $99 Documents</a></li>
                    </ul>
                </dropdown>

            <li>
                <form class="navbar-form navbar-left">
                    <button type="button" class="btn btn-default" v-on:click="showInventoryForm" v-bind:disabled="!orderDealerID">Inventory Forms</button>
                    <button type="button" class="btn btn-default" v-on:click="showChangeOrderModal" v-bind:disabled="!changeableOrder" v-bind:order-uuid="orderCurrent.id">Change Order</button>
                    <button type="button" class="btn btn-default" v-on:click="uiToolsShowCancellationRequestForm" v-if="isCancellationRequestAvailable">Request Cancellation</button>
                </form>
            </li>
        </navbar>
    </div>

</template>

<script type="text/babel">
    import Navbar from 'vue-strap/src/Navbar.vue'
    import Dropdown from 'vue-strap/src/Dropdown.vue'
    import DownloadDocumentButton from './partial/DownloadDocumentButton.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        components: {
            Navbar,
            Dropdown,
            DownloadDocumentButton
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderDealerID: 'dealerOrderForm/orderDealerID',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                changeableOrder: 'dealerOrderForm/changeableOrder'
            }),
            isCancellationRequestAvailable () {
                let statuses = ['signature_pending', 'signed', 'sale_generated', 'submitted']
                return typeof this.orderCurrent.status !== 'undefined' && statuses.indexOf(this.orderCurrent.status.id) !== -1
            }
        },
        methods: {
            ...mapActions({
                uiToolsShowSaveForm: 'dealerOrderForm/uiTools/uiToolsShowSaveForm',
                uiToolsShowCancellationRequestForm: 'dealerOrderForm/uiTools/uiToolsShowCancellationRequestForm',
                uiToolsShowLoadForm: 'dealerOrderForm/uiTools/uiToolsShowLoadForm',
                uiToolsSetStateInventoryForm: 'dealerOrderForm/uiTools/uiToolsSetStateInventoryForm',
                setOrderState: 'dealerOrderForm/setOrderState',
                showChangeOrderModal: 'dealerOrderForm/uiTools/showChangeOrderModal',
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            showInventoryForm() {
                this.uiToolsSetStateInventoryForm({'show': true})
            },
            changeSubmitMode(mode) {
                this.$emit('change-mode', {
                    mode,
                    reset: true
                })
                if (_.isUndefined(this.$parent.$refs.buildingStep.$refs.threedOrder)) return
                this.updateOrderBuilding({
                    'customBuildOptions': this.$parent.$refs.buildingStep.$refs.threedOrder.buildingOptions
                })
            },
            openDocument(document) {
                if (document === 'order-form' && this.orderDealerID) {
                    let url = '/documents/order-form?id=' + this.orderDealerID
                    window.open(url, '_blank')
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .btn[disabled] {
        opacity: 1;
    }

    .navbar {
        margin-bottom: 0px !important;
        border-radius: 0px !important;
    }

    .navbar-form {
        margin-left: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
    }

    .form-mode {
        margin-top: -3px;
    }

</style>