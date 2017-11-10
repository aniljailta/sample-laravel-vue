<template>

    <div class="form-progress">
        <div class="list-group text-center">
            <a v-on:click.prevent="goToStep('dealer')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'dealer' ? 'active' : '']">
                <h4 class="list-group-item-heading">Step 1 <span v-if="valid.dealer === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Dealer Information</p>
            </a>
            <a v-on:click.prevent="goToStep('building')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'building' ? 'active' : '']">
                <h4 class="list-group-item-heading">Step 2 <span v-if="valid.building === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Building Information</p>
            </a>
            <a v-on:click.prevent="goToStep('order')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'order' ? 'active' : '']">
                <h4 class="list-group-item-heading">Step 3 <span v-if="valid.order === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Order Information</p>
            </a>
            <a v-on:click.prevent="goToStep('attachments')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'attachments' ? 'active' : '']">
                <h4 class="list-group-item-heading">Step 4 <span v-if="valid.attachments === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Documents
                    <span class="badge"
                          :class="{'success': countUploadedAttachments === countRequiredAttachments, 'warning': countUploadedAttachments < countRequiredAttachments}"
                          v-if="orderCurrent.id !== null">
                        {{ countUploadedAttachments }}/{{ countRequiredAttachments }}
                    </span>
                </p>
            </a>
            <a v-on:click.prevent="goToStep('submit')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'submit' ? 'active' : '']">
                <h4 class="list-group-item-heading">Step 5 <span v-if="valid.submit === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Submit</p>
            </a>

            <a v-on:click.prevent="goToStep('final')" class="list-group-item col-xs-12 col-sm-2" v-bind:class="[currentStep == 'final' ? 'active' : '']" v-if="finalStep">
                <h4 class="list-group-item-heading">Step 6 <span v-if="valid.final === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span></h4>
                <p class="list-group-item-text">Final</p>
            </a>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'

    export default {
        name: 'main-step-progress',
        data() {
            return {
            }
        },
        props: {
            finalStep: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            ...mapGetters({
                valid: 'dealerOrderForm/orderValidation',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                countRequiredAttachments: 'dealerOrderForm/countRequiredAttachments',
                countUploadedAttachments: 'dealerOrderForm/countUploadedAttachments'
            }),
            currentStep() {
                return this.$parent.currentStep
            }
        },
        methods: {
            goToStep(step) {
                this.$emit('go-to-step', {
                    step, options: {
                        validateDeps: true
                    }
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .form-progress-actions {
        button.dropdown-toggle {
            width: 100%;
            height: 100%;
            // border-radius: 0px;
        }
    }
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .form-progress-actions {
        .btn-group {
            height: 2em;
        }

        .form-progress-action__generate {
            padding-bottom: 1px;
        }

        .form-progress-action__sign {
            padding-top: 1px;
        }
    }

    .badge.success {
        background: #5cb85c;
    }

    .badge.warning {
        background: red;
    }
</style>