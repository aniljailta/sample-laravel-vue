<template>

    <button id="customButton"
            class="btn btn-sm btn-success"
            v-on:click.prevent="makePayment"
            v-bind:disabled="processing || paymentToken"
            v-bind:class="{ 'disabled': processing || paymentToken }">
        <i class="fa fa-usd" aria-hidden="true" v-if="!processing"></i>
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true" v-show="processing"></i>
        Collect the deposit
    </button>

</template>

<script type="text/babel">
    /*global StripeCheckout*/
    let stripe

    export default {
        data() {
            return {
                processing: false,
                loaded: false,
                token: {},
                _stripePublishableKey: null
            }
        },
        props: {
            paymentToken: {
                default: null
            },
            email: {
                default: ''
            },
            amount: {
                default: 0
            }
        },
        created() {
            this._stripePublishableKey = window.document.getElementById('_stripePublishableKey').content
        },
        methods: {
            embed() {
                let self = this
                let srtipeScript = document.createElement('script')
                srtipeScript.setAttribute('type', 'text/javascript')
                srtipeScript.setAttribute('src', 'https://checkout.stripe.com/checkout.js')
                document.getElementsByTagName('head')[0].appendChild(srtipeScript)

                function configureStripe() {
                    stripe = StripeCheckout.configure({
                        key: self._stripePublishableKey,
                        image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                        locale: 'auto',
                        token(token) {
                            self.token = token
                            // You can access the token ID with `token.id`.
                            // Get the token ID to your server-side code for use.
                        },
                        opened() {
                        },
                        closed() {
                            if (!_.isEmpty(self.token)) {
                                self.$emit('complete-payment', self.token)
                            }

                            self.processing = false
                        }
                    })

                    self.loaded = true
                    if (self.processing) {
                        self.makeCheckout()
                    }
                }

                srtipeScript.onload = configureStripe
                srtipeScript.onreadystatechange = function() {
                    if (this.readyState === 'complete') {
                        configureStripe()
                    }
                }
            },
            makePayment() {
                this.processing = true
                if (this.loaded) {
                    this.makeCheckout()
                } else {
                    this.embed()
                }
            },
            makeCheckout() {
                stripe.open({
                    name: 'Title',
                    description: 'Description',
                    email: this.email,
                    amount: parseInt(this.filters.formatNumber(this.amount, 2, '', ''))
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>