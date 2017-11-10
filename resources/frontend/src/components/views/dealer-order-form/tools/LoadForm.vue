<template>

    <div>
        <div class="panel-heading">
            <h2 class="panel-title">Load Order Form</h2>
        </div>

        <div class="panel-body overlayable">
            <div style="text-align: center">
                <button type="button" class="btn btn-default" @click="uiToolsHideLoadForm">Close</button>
                <button type="button" class="btn btn-primary" @click="search" :disabled="dataProcess.running">Search
                </button>
            </div>

            <div class="form-container">
                <data-process :process="dataProcess" :with_loader="true"></data-process>

                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <div>
                                <h4><u>Customer Information</u></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" :class="{'invalid': $v.customer.firstName.$error}" placeholder="First Name"
                                               :value="customer.firstName"
                                               @input="$v.customer.firstName.$touch"
                                               @blur="$v.customer.firstName.$touch"
                                               v-model="customer.firstName">
                                        <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.name === false" class="alert alert-danger" role="alert">Enter a valid First Name.</div>
                                        <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" :class="{'invalid': $v.customer.lastName.$error}" placeholder="Last Name"
                                               :value="customer.lastName"
                                               @input="$v.customer.lastName.$touch"
                                               @blur="$v.customer.lastName.$touch"
                                               v-model="customer.lastName">
                                        <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.name === false" class="alert alert-danger" role="alert">Enter a valid Last Name.</div>
                                        <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="form-customer-email">Email</label>
                                        <input type="text" class="form-control" :class="{'invalid': $v.customer.email.$error}" id="form-customer-email" placeholder="Email"
                                               @input="$v.customer.email.$touch"
                                               @blur="$v.customer.email.$touch"
                                               v-model="customer.email">
                                        <div v-if="$v.customer.email.$dirty && $v.customer.email.email === false" class="alert alert-danger" role="alert">Enter a valid Email.</div>
                                        <div v-if="$v.customer.email.$dirty && $v.customer.email.required === false" class="alert alert-danger" role="alert">
                                            The Email field is required when none of customer first name / customer last name are present.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <order-list ref="orderList" :orders="orders"></order-list>
            </div>
        </div>

    </div>

</template>

<script type="text/babel">
    import loadFormValidation from 'src/validations/dealer-order-form/load-form-tools.validation.js'
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import OrderList from './OrderList.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        extends: baseDataItem,
        components: {
            OrderList,
            baseDataItem
        },
        data() {
            return {
                orders: [],
                customer: {
                    firstName: null,
                    lastName: null,
                    email: null
                },
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        validations: loadFormValidation,
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderDealerID: 'dealerOrderForm/orderDealerID',
                getUiToolsStateLoadForm: 'dealerOrderForm/uiTools/getUiToolsStateLoadForm'
            })
        },
        methods: {
            ...mapActions({
                loadDealerOrder: 'dealerOrderForm/loadDealerOrder',
                searchOrders: 'dealerOrderForm/searchOrders',
                setOrderState: 'dealerOrderForm/setOrderState',
                uiToolsHideLoadForm: 'dealerOrderForm/uiTools/uiToolsHideLoadForm',
                uiToolsSetStateLoadForm: 'dealerOrderForm/uiTools/uiToolsSetStateLoadForm'
            }),
            search() {
                this.$v.$touch()
                if (this.$v.$error) return

                let self = this
                self.searchOrders({
                    payload: {
                        customer: self.customer,
                        dealerId: self.orderDealerID
                    },
                    beforeCb() {
                        self.run({text: 'Searching..', type: 'form'})
                        self.uiToolsSetStateLoadForm('idle')
                        self.orders = []
                    },
                    successCb(response) {
                        self.orders = response
                        self.$emit('data-process-update', {
                            running: false
                        })
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .panel-body {
        position: relative;
    }

    .form-group {
        margin-bottom: 15px!important;
    }
</style>