<template>

    <div>
        <div class="panel-heading">
            <h2 class="panel-title">Save Order Form</h2>
        </div>

        <div class="panel-body">
            <div style="text-align: center">
                <div class="row" v-if="needContinue">
                    <em>Please save the order to continue.</em>
                </div>
                <button type="button" class="btn btn-default" @click="uiToolsHideSaveForm">Close</button>
                <button type="button" class="btn btn-primary" @click="save" :disabled="dataProcess.running">Save</button>
            </div>

            <div class="form-container">
                <data-process :process="dataProcess" :with_loader="true"></data-process>
                
                <div class="row text-center generate-button" v-if="canContinue">
                    <button type="button"
                            class="btn btn-success"
                            v-if="onContinue === 'dofGenerateOrder'"
                            @click="next">
                        Sign Documents <i class="fa fa-arrow-right fa-fw"></i>
                    </button>
                    <button type="button"
                            class="btn btn-success"
                            v-if="onContinue === 'dofGenerateReceipt'"
                            @click="next">
                        Generate Receipt <i class="fa fa-arrow-right fa-fw"></i>
                    </button>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="orderCurrent.id">
                        <div class="form-group">
                            <label for="save-as">Save as</label>
                            <div class="btn-group btn-group-lg1" data-toggle="buttons" id="save-as">
                                <label :class="{'active': saveAs === 'existing'}" class="btn btn-default">
                                    <input type="radio" name="saveAs" autocomplete="off"
                                           v-model="saveAs"
                                           :value="'existing'"
                                           :checked="saveAs === 'existing'"> Existing
                                </label>
                                <label :class="{'active': saveAs === 'new'}" class="btn btn-default">
                                    <input type="radio" name="saveAs" autocomplete="off"
                                           v-model="saveAs"
                                           :value="'new'"
                                           :checked="saveAs === 'new'"> New
                                </label>
                            </div>
                        </div>
                    </div>

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
                                            The customer's email address is required if the customer's first and last name are not available.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <label for="save-form-notes">Notes</label>
                            <small><em>Add a note or description to help identify this entry</em></small>
                            <textarea type="text"
                                      class="form-control"
                                      id="save-form-notes"
                                      placeholder="Notes"
                                      v-model="dealerNotes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import saveFormValidation from 'src/validations/dealer-order-form/save-form-tools.validation.js'
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        extends: baseDataItem,
        components: {},
        data() {
            return {
                saveAs: 'existing',
                customer: {
                    firstName: null,
                    lastName: null,
                    email: null
                },
                dealerNotes: null,
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        validations: saveFormValidation,
        mounted() {
            this.dealerNotes = this.orderCurrent.dealerNotes
            this.customer = {
                email: this.orderCustomer.email,
                firstName: this.orderCustomer.firstName,
                lastName: this.orderCustomer.lastName
            }
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                orderCustomer: 'dealerOrderForm/orderCustomer',
                getUiToolsSaveForm: 'dealerOrderForm/uiTools/getUiToolsSaveForm',
                getUiToolsStateSaveForm: 'dealerOrderForm/uiTools/getUiToolsStateSaveForm'
            }),
            needContinue() {
                if (!this.getUiToolsSaveForm.onContinue) return false
                if (!(this.getUiToolsSaveForm.onContinue === 'dofGenerateOrder' || this.getUiToolsSaveForm.onContinue === 'dofGenerateReceipt')) return false
                return true
            },
            canContinue() {
                if (!this.dataProcess.success) return false
                if (!this.getUiToolsSaveForm.onContinue) return false
                if (!(this.getUiToolsSaveForm.onContinue === 'dofGenerateOrder' || this.getUiToolsSaveForm.onContinue === 'dofGenerateReceipt')) return false
                return true
            },
            onContinue() {
                return this.getUiToolsSaveForm.onContinue
            }
        },
        methods: {
            ...mapActions({
                saveDealerOrder: 'dealerOrderForm/saveDealerOrder',
                setOrderState: 'dealerOrderForm/setOrderState',
                updateOrderCustomer: 'dealerOrderForm/updateOrderCustomer',
                // ui tools
                uiToolsSetStateSaveForm: 'dealerOrderForm/uiTools/uiToolsSetStateSaveForm',
                uiToolsHideSaveForm: 'dealerOrderForm/uiTools/uiToolsHideSaveForm'
            }),
            close() {
                this.uiToolsHideSaveForm()
            },
            save() {
                this.$v.$touch()
                if (this.$v.$error) return

                let self = this

                // before save
                self.run({text: 'Saving..', type: 'form'})
                self.uiToolsSetStateSaveForm({state: 'idle'})
                let views = {}
                new Promise(resolve => {
                    let threeD = this.$parent.$parent.$parent.$refs.buildingStep
                    views = threeD.$refs.threedOrder && threeD.threedPass === 'yes' ? threeD.$refs.threedOrder.getViews() : null
                    resolve()
                }).then(() => {
                   // save
                    self.saveDealerOrder({
                        saveAs: self.saveAs,
                        withEmpty: true,
                        dealerNotes: self.dealerNotes,
                        customer: self.customer,
                        views: views
                    }).then(response => {
                        self.saveAs = 'existing'
                        self.$emit('data-process-update', {
                            running: false,
                            success: 'Order successfully saved.'
                        })
                    }).catch(response => {
                        self.$emit('data-failed', response)
                    })
                })
            },
            next() {
                let event = this.getUiToolsSaveForm.onContinue
                this.close()
                this.$bus.$emit(event)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .panel-body {
        position: relative;
    }

    .generate-button {
        margin-bottom: 0.5em;
    }

    .form-group {
        margin-bottom: 15px!important;
    }
</style>