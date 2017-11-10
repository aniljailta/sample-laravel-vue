<template>

    <div class="row">
        <div class="col-md-12">
            <div class="list-group overlayable">
                <data-process :process="dataProcess" :with_loader="true"></data-process>
                <div class="list-group-item item-heading">
                    <div class="col-xs-2 plr-none text-left">
                        <button class="btn btn-default"
                                v-on:click.prevent="goToStep('previous')"><i class="fa fa-arrow-left fa-fw"></i> Previous
                        </button>
                    </div>
                    <div class="col-xs-6 plr-none text-center">
                        <h4>Documents</h4>
                    </div>
                    <div class="col-xs-4 plr-none text-right">
                        <button v-if="nextStep('next')" class="btn"
                                :disabled="signaturePingActive"
                                v-bind:class="buttonSuggesting"
                                v-on:click.prevent="goToStep('next')">Next <i class="fa fa-arrow-right fa-fw"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="list-group-item sub-step">
                    <!-- [Signature Method] -->
                    <h4 class="list-group-item-heading">Please choose signature method:</h4>
                    <div class="btn-group btn-group-lg1" data-toggle="buttons">
                        <label :class="{'active': signatureMethodId === 'manual'}" class="btn btn-default">
                            <input @click="change({'signatureMethodId': $event.target.value})"
                                   type="radio"
                                   name="signatureMethodId"
                                   :value="'manual'"
                                   :checked="signatureMethodId === 'manual'">Manual
                        </label>
                        <label :class="{'active': signatureMethodId === 'e_signature'}" class="btn btn-default">
                            <input @click="change({'signatureMethodId': $event.target.value})"
                                   type="radio"
                                   name="signatureMethodId"
                                   :value="'e_signature'"
                                   :checked="signatureMethodId === 'e_signature'">E-Signature
                        </label>
                    </div>
                    <div v-if="$v.signatureMethodId.$dirty && $v.signatureMethodId.required === false"
                         class="alert alert-danger" role="alert">The type of signature method must be indicated.
                    </div>
                    <!-- [/Signature Method] -->

                </div>
                <div class="list-group-item sub-step" v-if="!orderId && signatureMethodId !== null">
                    <button class="btn btn-lg btn-success" @click="uiToolsShowSaveForm">Save order to continue</button>
                </div>
                <div class="list-group-item sub-step" v-if="orderId && signatureMethodId !== null && countRequiredAttachments > 0">

                    <div class="alert alert-warning" v-if="orderCurrent.isChangeOrder === true">
                        <strong>Note: </strong>If the building has changed, be sure to change the building configuration.
                    </div>

                    <!-- [Files] -->
                    <h4 class="list-group-item-heading">
                        To continue with
                        <span v-if="signatureMethodId === 'manual'">manual signature</span>
                        <span v-if="signatureMethodId === 'e_signature'">an e-signature</span>
                        the following documents must be uploaded:
                    </h4>

                    <div v-if="$v.attachedCategories.signedOrderDocuments && $v.attachedCategories.signedOrderDocuments.$dirty && $v.attachedCategories.signedOrderDocuments.accepted === false"
                         class="alert alert-danger"
                         role="alert">
                        You need to attach "{{ fileCategories['signedOrderDocuments'].title }}"
                    </div>
                    <div v-if="$v.attachedCategories.signedBuildingConfiguration && $v.attachedCategories.signedBuildingConfiguration.$dirty && $v.attachedCategories.signedBuildingConfiguration.accepted === false"
                         class="alert alert-danger"
                         role="alert">
                        You need to attach "{{ fileCategories['signedBuildingConfiguration'].title }}"
                    </div>
                    <div v-if="$v.attachedCategories.signedNeighborRelease && $v.attachedCategories.signedNeighborRelease.$dirty && $v.attachedCategories.signedNeighborRelease.accepted === false"
                         class="alert alert-danger"
                         role="alert">
                        You need to attach "{{ fileCategories['signedNeighborRelease'].title }}"
                    </div>
                    <div v-if="$v.attachedCategories.driverLicense && $v.attachedCategories.driverLicense.$dirty && $v.attachedCategories.driverLicense.accepted === false"
                         class="alert alert-danger"
                         role="alert">
                        You need to attach "{{ fileCategories['driverLicense'].title }}"
                    </div>

                    <div class="attachments-container">
                        <file-manager v-for="(attachment, attachmentIndex) in attachmentsMap" :key="attachment.key"
                                      v-if="attachment.files.length > 0 || attachment.canGenerate || attachment.canUpload"
                                      ref="fileManager"
                                      class="col-xs-12 col-sm-3"
                                      :attachment="attachment"
                                      :isValid="getValidationByAttachment(attachment)"
                                      :storableId="orderCurrent.id">
                        </file-manager>
                        <div class="clearfix"></div>
                    </div>
                    <!-- [/Files] -->
                </div>

                <div class="list-group-item sub-step" v-if="signatureMethodId === 'e_signature' && eSignAvailable">
                    <h4 class="list-group-item-heading">E-Signature:</h4>

                    <div class="alert alert-warning">
                        E-signatures  are now entirely electronic, for RTO contracts, JMAG LLC must sign the contract before the order can be submitted,
                        and you do not need to mail the contract to JMAG LLC (however, checks do need to be mailed).
                        Dealer's will receive an email when all parties have signed.
                    </div>

                    <button class="btn btn-warning" v-on:click.prevent="esign('embed')" v-if="esignedOrderDocumentsAttachment.allowToEsignEmbed && !isEsignedByRto && !isEsignedByCustomer">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        E-sign Documents
                    </button>
                    <button class="btn btn-warning" v-on:click.prevent="esign('email')" v-if="esignedOrderDocumentsAttachment.allowToEsignEmail">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        E-sign Documents via Email
                    </button>
                    <div v-if="$v.attachedCategories.eSignedOrderDocuments && $v.attachedCategories.eSignedOrderDocuments.$dirty && $v.attachedCategories.eSignedOrderDocuments.accepted === false"
                         class="alert alert-danger"
                         role="alert">
                        A signed document is required to continue.
                    </div>

                    <div v-if="esignedOrderDocumentsAttachment.files.length > 0">
                        <br>
                        <!-- wrap esignedOrderDocumentsAttachment for reset cache of data and components  -->
                        <file-manager v-for="(eda, edaIndex) in [esignedOrderDocumentsAttachment]"
                                      :key="eda.key"
                                      class="col-xs-12 col-sm-3"
                                      :attachment="eda"
                                      :isValid="getValidationByAttachment(eda)"
                                      :storableId="orderCurrent.id">
                        </file-manager>
                        <div class="clearfix"></div>
                    </div>
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
                                :disabled="signaturePingActive"
                                v-on:click.prevent="goToStep('next')">Next <i class="fa fa-arrow-right fa-fw"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    /*global swal*/
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'
    import FileManager from './attachments-step/FileManager.vue'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import attachmentsStepValidation from 'src/validations/dealer-order-form/attachments-step.validation.js'

    export default {
        extends: baseDataItem,
        name: 'attachments-step',
        mixins: [vuelidateAnyerror],
        components: {FileManager},
        validations: attachmentsStepValidation,
        data() {
            return {
                id: null,
                dataProcess: {
                    type: 'form',
                    running: false
                },
                statusId: null,
                statusCheckRunning: true,
                pingIntervalId: null
            }
        },
        beforeCreate() {
            this.$bus.$on('dofFileSigned', (id) => this.reload())
            this.$bus.$on('dofSignaturePending', (id) => this.reload())
        },
        created() {
            let self = this
            this.receiveFileCategories({
                successCb() { self.$bus.$emit('enable-form') }
            })
            this.$watch('$anyerror', (value) => {
                this.updateOrderValidation({'attachments': !value})
            })
            this.$watch('signaturePingActive', (value) => {
                let self = this
                if (value === true && this.statusCheckRunning) {
                    self.pingIntervalId = setInterval(() => {
                        this.orderCurrent.pingLoaderVisible = true
                        this.ping()
                    }, 3000)
                } else {
                    clearInterval(self.pingIntervalId)
                }
            })
        },
        beforeDestroy() {
            let self = this
            clearInterval(self.pingIntervalId)
        },
        computed: {
            ...mapGetters({
                orderStateMode: 'dealerOrderForm/orderStateMode',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                signatureMethodId: 'dealerOrderForm/orderSignatureMethodId',
                saleType: 'dealerOrderForm/orderSaleType',
                paymentType: 'dealerOrderForm/orderPaymentType',
                deliveryRemarks: 'dealerOrderForm/orderDeliveryRemarks',
                fileCategories: 'dealerOrderForm/files/categories',
                // requiredAttachments: 'dealerOrderForm/requiredAttachments',
                countRequiredAttachments: 'dealerOrderForm/countRequiredAttachments',
                attachedCategories: 'dealerOrderForm/attachedCategories',
                attachments: 'dealerOrderForm/attachments',
                attachmentsMap: 'dealerOrderForm/attachmentsMap',
                attachmentsPerCategory: 'dealerOrderForm/attachmentsPerCategory',
                esignedOrderDocumentsAttachment: 'dealerOrderForm/eSignedOrderDocumentsAttachment',
                orderPaymentType: 'dealerOrderForm/orderPaymentType',
                signaturePingActive: 'dealerOrderForm/signaturePingActive',
                getRtoSignaturePendingStatus: 'dealerOrderForm/getRtoSignaturePendingStatus'
            }),
            // check all required file categories for existed files
            eSignAvailable() {
                // if(!_.isUndefined(this.$parent.$refs.buildingStep.$refs.threedOrder)) return true
                return _.every(this.attachmentsMap, required => required.files.length > 0)
            },
            // this need for for 're-render' file uploader correctly..
            loadedAt() {
                if (this.orderCurrent) {
                    return this.orderCurrent.loadedAt
                }
                return null
            },
            orderId() {
                return (this.orderCurrent && this.orderCurrent.id)
            },
            buttonSuggesting() {
                return {'btn-default': true}
            },
            isEsignedByCustomer() {
                return this.orderCurrent && this.orderCurrent.signerRole === 'customer' && this.orderCurrent.isEsigned
            },
            isEsignedByRto() {
                return this.orderCurrent && this.paymentType === 'rto' && this.orderCurrent.isEsigned
            }
        },
        methods: {
            ...mapActions({
                saveOrderChanges: 'dealerOrderForm/saveOrderChanges',
                uiToolsShowSaveForm: 'dealerOrderForm/uiTools/uiToolsShowSaveForm',
                loadDealerOrder: 'dealerOrderForm/loadDealerOrder',
                updateOrderSync: 'dealerOrderForm/updateOrderSync',
                receiveFileCategories: 'dealerOrderForm/files/receiveFileCategories',
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder',
                updateOrderValidation: 'dealerOrderForm/updateOrderValidation',
                // esignatures
                esignEmbedShow: 'dealerOrderForm/esign/esignEmbedShow',
                esignEmailShow: 'dealerOrderForm/esign/esignEmailShow',
                getOrderStatus: 'dealerOrderForm/getOrderStatus'
            }),
            $validate(steps, options) {
                if (options.$reset) this.$v.$reset()
                if (options.$touch) this.$v.$touch()
                return !this.$anyerror
            },
            getValidationByAttachment(attachment) {
                let categoryCamelCase = _.camelCase(attachment.categoryId)
                if (this.$v.attachedCategories[categoryCamelCase]) {
                    return !this.$v.attachedCategories[categoryCamelCase].$error
                }
                return true
            },
            ping() {
                let self = this
                this.getOrderStatus({id: self.orderCurrent.id})
                    .then(response => {
                        self.statusId = response.body.statusId
                        if (self.statusId === 'signed') {
                            this.statusCheckRunning = false
                            clearInterval(self.pingIntervalId)
                            self.reload()
                        }
                    })
                    .catch(error => {
                        self.$emit('data-failed', error)
                    })
            },
            reload() {
                let self = this
                this.loadDealerOrder({
                    payload: {
                        id: self.orderCurrent.id
                    },
                    beforeCb() {
                        self.run({text: 'Refreshing..', type: 'form'})
                    },
                    successCb() {
                        self.$nextTick(function () {
                            self.$emit('data-process-update', {
                                running: false
                            })
                            self.updateOrderSync({merging: 'done'})
                        })
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            },
            esign(type) {
                return this.saveOrderChanges().then(params => {
                    if (type === 'embed') this.esignEmbedShow()
                    if (type === 'email') this.esignEmailShow()
                }).catch((err) => {
                    if (err) {
                        if (_.isObject(err)) {
                            err = _.values(err)
                        }
                        let error = _.isArray(err) ? err.join('\r\n') : err
                        swal('Error', error, 'error')
                    }
                })
            },
            change(object) {
                console.log(object)
                this.updateOrderOrder(object)
                this.$nextTick(() => {
                    this.$v.$touch()
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
</style>