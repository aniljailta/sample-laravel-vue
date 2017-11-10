<template>

    <div class="col-xs-12 plr-none">
        <!-- currentSteps -->

        <div class="form-sub-progress ">
            <div class="list-group text-center col-xs-12">
                <a class="list-group-item" v-bind:class="[currentStep == 'customer-info' ? 'active' : '']" v-on:click="goToStep('customer-info', { validateDeps: false })">
                    <span class="badge">{{ currentStepId('customer-info') }}</span> Customer Information
                    <span v-if="$refs.customerStage && $refs.customerStage.$anyerror"><i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                </a>
                <a class="list-group-item" v-bind:class="[currentStep === 'order-info' ? 'active' : '']" v-on:click="goToStep('order-info', { validateDeps: false })">
                    <span class="badge">{{ currentStepId('order-info') }}</span> Order Information
                    <span v-if="$refs.orderStage && $refs.orderStage.$anyerror"><i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                </a>
                <a class="list-group-item" v-bind:class="[currentStep === 'purchase-method' ? 'active' : '']" v-on:click="goToStep('purchase-method', { validateDeps: false })">
                    <span class="badge">{{ currentStepId('purchase-method') }}</span> Purchase Method
                    <span v-if="$refs.purchaseMethodStage && $refs.purchaseMethodStage.$anyerror"><i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                </a>
                <a class="list-group-item" v-bind:class="[currentStep === 'rto-info' ? 'active' : '']" v-on:click="goToStep('rto-info', { validateDeps: false })" v-if="paymentType === 'rto'">
                    <span class="badge">{{ currentStepId('rto-info') }}</span> RTO Application
                    <span v-if="$refs.rtoStage && $refs.rtoStage.$anyerror"><i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                </a>
            </div>
        </div>

        <div class="clearfix"></div>
        <br>

        <order-stage ref="orderStage" v-show="currentStep === 'order-info'"></order-stage>
        <customer-stage ref="customerStage" v-show="currentStep === 'customer-info'"></customer-stage>
        <purchase-method-stage ref="purchaseMethodStage" v-show="currentStep === 'purchase-method'"></purchase-method-stage>

        <template v-if="paymentType === 'rto'">
            <rto-stage ref="rtoStage" v-show="currentStep === 'rto-info'"></rto-stage>
        </template>
    </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import subSteps from 'src/mixins/sub-steps'
    import customerStage from './order-step/CustomerStage.vue'
    import orderStage from './order-step/OrderStage.vue'
    import purchaseMethodStage from './order-step/PurchaseMethodStage.vue'
    import rtoStage from './order-step/RtoStage.vue'

    export default {
        name: 'order-step',
        mixins: [subSteps],
        components: {
            customerStage,
            orderStage,
            purchaseMethodStage,
            rtoStage
        },
        data() {
            return {
                currentStep: 'customer-info'
            }
        },
        created() {
            this.$bus.$on('dofOrderLoaded', () => {
                this.goToStep('customer-info')
            })
        },
        computed: {
            ...mapGetters({
                paymentType: 'dealerOrderForm/orderPaymentType'
            })
        },
        methods: {
            ...mapActions({
                updateOrderValidation: 'dealerOrderForm/updateOrderValidation'
            }),
            currentStepId: function(currentStep) {
                if (currentStep === 'customer-info') return 1
                if (currentStep === 'order-info') return 2
                if (currentStep === 'purchase-method') return 3
                if (currentStep === 'rto-info') return 4
                if (currentStep === 'collect-deposit') return (this.paymentType === 'rto' ? 5 : 4)
            },
            nextStep(direction, currentStep) {
                currentStep = currentStep || this.currentStep
                let nextStep

                if (direction === 'next') {
                    if (currentStep === 'customer-info') nextStep = 'order-info'
                    if (currentStep === 'order-info') nextStep = 'purchase-method'
                    if (currentStep === 'purchase-method') {
                        if (this.paymentType === 'rto') nextStep = 'rto-info'
                    }
                }

                if (direction === 'previous') {
                    if (currentStep === 'rto-info') nextStep = 'purchase-method'
                    if (currentStep === 'purchase-method') nextStep = 'order-info'
                    if (currentStep === 'order-info') nextStep = 'customer-info'
                }

                return nextStep || null
            },
            // run/check values from batch of $validator fields/or sub-components
            $validate(steps, options) {
                options = options || {}
                if (_.isString(steps) && !_.isArray(steps)) steps = [steps]
                if (_.isEmpty(steps)) {
                    steps = ['customer-info', 'order-info', 'purchase-method', 'rto-info']
                }
                let alias = {
                    'customer-info': 'customerStage',
                    'order-info': 'orderStage',
                    'purchase-method': 'purchaseMethodStage',
                    'rto-info': 'rtoStage'
                }

                let validSteps = _.filter(steps, step => {
                    if (this.$refs[alias[step]] && !_.isUndefined(this.$refs[alias[step]].$v)) {
                        if (options.$reset) this.$refs[alias[step]].$v.$reset()
                        if (options.$touch) this.$refs[alias[step]].$v.$touch()
                        return !this.$refs[alias[step]].$anyerror
                    }
                    return true
                })

                return (_.isEqual(validSteps, steps))
            },
            revalidate() {
                let valid = true
                if (this.$refs.orderStage.$anyerror) {
                    valid = false
                }
                if (this.$refs.customerStage.$anyerror) {
                    valid = false
                }
                if (this.$refs.purchaseMethodStage.$anyerror) {
                    valid = false
                }
                if (this.paymentType === 'rto' && this.$refs.rtoStage.$anyerror) {
                    valid = false
                }
                this.updateOrderValidation({'order': valid})
            }
        },
        watch: {
            currentStep(nextStep) {
                if (nextStep === 'rto-info') {
                    this.$refs.rtoStage.$emit('show-step')
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>