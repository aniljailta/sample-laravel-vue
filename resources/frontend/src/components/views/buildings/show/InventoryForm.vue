<template>

    <div style="display: initial">
        <modal :show="show"
               :modal-class="modalClass"
               :modal-style="modalStyle"
               :mask-style="maskStyle"
               :close-modal-method="closeModalMethod">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Inventory Form</h2>
                </div>

                <div class="panel-body">
                    <div class="form-container">
                        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
                        <div class="row" v-if="dataIsReady">
                            <div class="col-md-12 col-xs-12">
                                <h4 class="text-center">Dealers</h4>
                                <ul class="list-group dealer-list-container">

                                    <li class="list-group-item dealer-list-item" v-for="dealer in dealers">
                                        <div class="dealer-list-desc text-left">
                                            <strong><span style="color: #000">{{ dealer.businessName }}</span></strong><br/>
                                            <strong>{{ dealer.email }}</strong><br/>
                                            <strong>Cash sale deposit rate: </strong>{{ dealer.cashSaleDepositRate }}%<br/>
                                            <strong>Tax Rate: </strong>{{ dealer.taxRate }}%<br/>
                                        </div>
                                        <div><button type="button" class="btn btn-primary" v-on:click="downloadPdf(dealer)">Download PDF</button></div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="show = false">Close</button>
                </div>
            </div>

        </modal>

        <a class="btn btn-md btn-primary" v-on:click="selectInventoryFormDealer">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Inventory Form
        </a>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import apiDealers from 'src/api/dealers'
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'

    export default {
        name: 'building-inventory-list',
        extends: BaseDataItem,
        data() {
            return {
                show: false,
                modalClass: 'col-md-4 col-sm-6 col-xs-11',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                }
            }
        },
        components: {
            Modal
        },
        props: {
            id: {
                required: true,
                type: Number
            }
        },
        methods: {
            selectInventoryFormDealer() {
                // if (this.dealers === null) this.getAllDealers({})
                this.show = true
                apiDealers.get({
                    params: {
                        per_page: 99999
                    }
                }).then((response) => {
                    this.dealers = response.data.data
                    return response
                }).then((response) => {
                    this.$emit('data-ready')
                    return response
                }).catch((response) => {
                    this.$emit('data-failed', response)
                })
            },
            closeModalMethod() {
                this.show = false
            },
            downloadPdf(dealer) {
                window.open('/buildings/' + this.id + '/inventory-form?dealer_id=' + dealer.id)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    .dealer-list-container {
        max-height: 300px;
        overflow: auto;

        .dealer-list-item {
            vertical-align: middle;
            display: table;
            width: 100%;

            > div {
                vertical-align: middle;
                display: table-cell;
            }
    
            .dealer-list-desc {
                width: 100%;
            }
        }
    }

</style>