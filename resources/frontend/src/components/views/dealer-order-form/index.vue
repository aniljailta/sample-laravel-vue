<template>

    <div id="dealer-order-form">
        <esign/>
        <collect-deposit-modal v-if="collectDepositShowModal"/>
        <tools/>
        <download-documents/>
        <div class="container">
            <div class="border-order-form">
                <tool-bar ref="toolBar" v-show="showMainForm" @change-mode="changeMode"/>
                <order-state v-if="orderCurrent.id"
                             ref="orderState"
                             :order-current="orderCurrent"
                             v-show="showMainForm"/>

            </div>
        </div>

        <div id="loading" ref="loading" class="container text-center" v-show="mainLoading">
            <p class="text-muted">Loading...</p>
            <i class="fa fa-circle-o-notch fa-spin fa-5x text-muted"></i>
        </div>

        <div class="container" v-show="showMainForm">
            <main-step-progress ref="mainStepProgress" @go-to-step="changeStep" :finalStep="deliveryRemarks.mustCrossNeighboringProperty"/>
        </div>

        <div class="container" v-show="currentStep === 'attachments' && signaturePingActive">
            <div class="alert alert-success text-center">
                <i class="fa fa-circle-o-notch fa-spin text-muted"></i> Waiting for signed order forms...
            </div>
        </div>

        <div class="container" v-show="showMainForm" ref="dealerForm">

            <dealer-step ref="dealerStep" v-show="currentStep === 'dealer'" @go-to-step="changeStep"/>

            <building-step ref="buildingStep" v-show="currentStep === 'building'" @go-to-step="changeStep"/>

            <order-step ref="orderStep" v-show="currentStep === 'order'" @go-to-step="changeStep"/>

            <attachments-step ref="attachmentsStep" v-show="currentStep === 'attachments'" @go-to-step="changeStep"/>

            <submit-step ref="submitStep" v-show="currentStep === 'submit'" @submit-order="beforeOrderSubmitting" @go-to-step="changeStep"/>

            <final-step ref="finalStep" v-show="currentStep === 'final'" v-if="deliveryRemarks.mustCrossNeighboringProperty" @go-to-step="changeStep"/>

        </div>
    </div>

</template>

<script type="text/babel">
    /*global swal*/
    import {mapActions, mapGetters} from 'vuex'
    import store from 'src/vuex/modules/dealer-order-form'
    import subSteps from 'src/mixins/sub-steps'

    import OrderState from './partial/OrderState.vue'
    import MainStepProgress from './MainStepProgress.vue'
    import DealerStep from './DealerStep.vue'
    import BuildingStep from './BuildingStep.vue'
    import OrderStep from './OrderStep.vue'
    import AttachmentsStep from './AttachmentsStep.vue'
    import SubmitStep from './SubmitStep.vue'
    import FinalStep from './FinalStep.vue'

    import Tools from './tools'
    import ToolBar from './ToolBar.vue'
    import Esign from './esign/index.vue'
    import CollectDepositModal from './collect-deposit/CollectDepositModal.vue'
    import DownloadDocuments from './partial/DownloadDocuments.vue'

    export default {
        mixins: [subSteps],
        data() {
            return {
                mainLoading: true,
                showMainForm: false,
                currentStep: 'dealer'
            }
        },
        created() {
            // overwrite mixin's method, because we are not needed for next 'parent' method call
            this.$on('go-to-step', () => {})
            this.$bus.$on('enable-form', this.enableForm)

            this.setOrderState({ form: 'dealer' })
            this.getAllSettings({}).then(() => {
                this.enableForm()
            })
        },
        beforeCreate() {
            this.$store.registerModule('dealerOrderForm', store)
            this.$bus.$on('dofOrderLoaded', (param) => {
                if (param !== undefined && param.step !== undefined) {
                    this.goToStep(param.step)
                } else {
                    this.goToStep('dealer')
                }
            })
            this.$bus.$on('dofGenerateDocument', (generateParams) => {
                this.beforeGenerateDocument(generateParams)
            })
        },
        mounted() {
            this.enableForm()
        },
        components: {
            OrderState,

            MainStepProgress,
            DealerStep,
            BuildingStep,
            OrderStep,
            AttachmentsStep,
            SubmitStep,
            FinalStep,

            Tools,
            ToolBar,
            Esign,
            CollectDepositModal,
            DownloadDocuments
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                orderCustomer: 'dealerOrderForm/orderCustomer',
                orderSyncStatus: 'dealerOrderForm/orderSyncStatus',
                settingsGlobal: 'dealerOrderForm/settings/settingsGlobal',
                dealers: 'dealerOrderForm/dealers/dealers',
                options: 'dealerOrderForm/options/list',
                optionCategories: 'dealerOrderForm/options/categories',
                styles: 'dealerOrderForm/styles/list',
                orderRtoTerms: 'dealerOrderForm/orderTerms/orderRtoTerms',
                fileCategories: 'dealerOrderForm/files/categories',
                buildingPackages: 'dealerOrderForm/buildingPackages/list',
                collectDepositShowModal: 'dealerOrderForm/collectDeposit/showModal',
                deliveryRemarks: 'dealerOrderForm/orderDeliveryRemarks',
                signaturePingActive: 'dealerOrderForm/signaturePingActive'
            })
        },
        methods: {
            ...mapActions({
                saveOrderChanges: 'dealerOrderForm/saveOrderChanges',
                uiToolsSetStateSaveForm: 'dealerOrderForm/uiTools/uiToolsSetStateSaveForm',
                getAllSettings: 'dealerOrderForm/settings/getAllSettings',
                updateCollectDepositValidation: 'dealerOrderForm/collectDeposit/updateValidFlag',
                setOrderState: 'dealerOrderForm/setOrderState',
                computeCed: 'dealerOrderForm/computeCed',
                submitOrder: 'dealerOrderForm/submitOrder',

                computeOrderSummary: 'dealerOrderForm/computeOrderSummary',
                computeBuildingSummary: 'dealerOrderForm/computeBuildingSummary',
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            changeMode({ mode, reset }) {
                this.setOrderState({ mode: mode })

                if (reset) {
                    this.$nextTick(() => {
                        this.$validate(null, {$reset: true})
                    })
                }
            },
            enableForm() {
                let self = this
                // Check if all data was loaded
                let allGood = _.every([
                    'settingsGlobal',
                    'dealers',
                    'options',
                    'optionCategories',
                    'styles',
                    'orderRtoTerms',
                    'fileCategories'
                    // 'buildingPackages'
                ], function(el) {
                    return _.size(self[el]) > 0
                }, this)

                // If it was, enable the form
                if (allGood) {
                    // $(this.$refs.loading).hide()
                    // $(this.$refs.customerForm).fadeIn()
                    this.computeCed() // init ced dates (customer expects by date - step 3 - substep order date)
                    this.mainLoading = false
                    this.showMainForm = true
                }
            },
            changeStep({step, options}) {
                if (step === 'next') {
                    step = this.nextStep(step)
                }

                if (step === 'attachments' || step === 'submit' || step === 'final') {
                    this.changeMode({mode: 'submit'})
                    // go to step and validate only after switching mode
                    // nexttick or promise is required here
                    this.$nextTick(() => {
                        this.goToStep(step, {validateDeps: true})
                    })
                    return
                }

                this.goToStep(step, options)
            },
            nextStep(direction, currentStep) {
                currentStep = currentStep || this.currentStep
                let nextStep

                if (direction === 'next') {
                    if (currentStep === 'dealer') nextStep = 'building'
                    if (currentStep === 'building') nextStep = 'order'
                    if (currentStep === 'order') nextStep = 'attachments'
                    if (currentStep === 'attachments') nextStep = 'submit'
                    if (currentStep === 'submit' && this.deliveryRemarks.mustCrossNeighboringProperty) nextStep = 'final'
                }

                if (direction === 'previous') {
                    if (currentStep === 'final') nextStep = 'submit'
                    if (currentStep === 'submit') nextStep = 'attachments'
                    if (currentStep === 'attachments') nextStep = 'order'
                    if (currentStep === 'order') nextStep = 'building'
                    if (currentStep === 'building') nextStep = 'dealer'
                }

                return nextStep || null
            },
            $validate(steps, options) {
                if (_.isString(steps) && !_.isArray(steps)) steps = [steps]
                steps = steps || ['dealer', 'building', 'order', 'attachments', 'submit']
                if (this.deliveryRemarks.mustCrossNeighboringProperty) {
                    steps.push('final')
                }

                let validSteps = _.filter(steps, step => {
                    step = step + 'Step'
                    if (this.$refs[step]) {
                        return this.$refs[step].$validate(null, options)
                    }
                    return true
                })

                return (_.isEqual(validSteps, steps))
            },
            beforeGenerateDocument(generateParams) {
                let self = this

                new Promise(function (resolve) {
                    if (generateParams.validate) {
                        resolve({isValid: generateParams.validate(self)})
                    } else {
                        resolve({isValid: true})
                    }
                }).then(function (res) {
                    if (generateParams.before) generateParams.before()
                    if (res.isValid) {
                        self.generateDocument(generateParams)
                    } else {
                        swal({
                            title: 'Are you sure?',
                            text: 'Some required fields are missing. Would you still like to generate a document?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'Cancel',
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                self.generateDocument(generateParams)
                            } else {
                                generateParams.stop()
                            }
                        })
                    }
                })
            },
            beforeOrderSubmitting(item) {
                let self = this

                new Promise(function (resolve) {
                    let allValid = self.$validate(null, { $touch: true })
                    resolve(allValid)
                }).then(function (allValid) {
                    if (allValid) {
                        self.submitOrder(item)
                    } else {
                        swal({
                            title: 'Warning',
                            text: 'Some required fields are missing. Please correct and re-submit.',
                            type: 'warning',
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Close'
                        })
                    }
                })
            },
            // before generating document we need to check current order state (any changes)
            // if order was changed after last save - force save order
            generateDocument(handler) {
                return this.saveOrderChanges()
                    .then(generate => {
                        this.$nextTick(() => {
                            this.$bus.$emit('dofDownload', handler.success())
                        })
                    })
                    .catch(response => {
                        handler.stop(response)
                    })
            },
            updateOrder() {
                let views = {}
                let threedRefs = this.$refs.buildingStep
                new Promise(resolve => {
                    views = threedRefs.$refs.threedOrder && threedRefs.threedPass === 'yes' ? threedRefs.$refs.threedOrder.getViews() : null
                    resolve()
                }).then(() => {
                   // save
                    this.updateOrderBuilding({
                        'customBuildOptions': threedRefs.$refs.threedOrder.buildingOptions,
                        views: views
                    })
                })
            }
        },
        watch: {
            orderSyncStatus(newVal, oldVal) {
                if (newVal === 'loaded') {
                    this.$validate(null, {$reset: true})
                    this.updateCollectDepositValidation(null)
                }
            },
            currentStep(nextStep, oldStep) {
                if (nextStep === 'submit') {
                    this.computeOrderSummary()
                    this.computeBuildingSummary()
                }
                if (nextStep === 'order') {
                    this.updateOrder()
                }
            }
        },
        destroyed() {
            this.$store.unregisterModule('dealerOrderForm')
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .page-header {
        padding-bottom: 0px;
        margin: 0.5em 0 0.5em;
        border-bottom: 0px;
    }
    #dealer-order-form {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        font-size: 14px!important;
        line-height: 1.42857143;
        color: #333!important;
    }
    #dealer-order-form .border-order-form {
        border-radius: 4px ;
        background: #f7f9fa;
    }
</style>