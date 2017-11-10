<template>
    <div>

        <dealer-map v-bind:show="showMap"
                    v-bind:dealers="dealers"
                    v-on:select-dealer="selectDealer"></dealer-map>

            <div class="row">
                <div class="col-md-6 col-xs-12" style="margin-top: 1em">
                    <div class="form-group">
                        <label for="business-name">Dealer selection *</label>
                        <select name="dealer"
                                class="form-control"
                                v-bind:class="{ 'invalid': $v.dealer.id.$error }"
                                v-bind:value="dealer.id"
                                @blur="$v.dealer.id.$touch()"
                                @change.prevent="selectDealer($event.target.value)">
                            <option v-bind:value="null" selected="selected" disabled>Select...</option>
                            <option v-for="(dealer, dealer_id) in dealers" v-bind:value="dealer_id">{{ dealer.businessName }}</option>
                        </select>
                        <div v-if="$v.dealer.id.$dirty && !$v.dealer.id.required" class="alert alert-danger" role="alert">The dealer is required</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="business-name">Business Name</label>
                        <input type="text" class="form-control" id="business-name" placeholder="Business Name" v-bind:value="dealer.businessName" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="business-address">Address</label>
                        <input type="text" class="form-control" id="business-address" placeholder="Address" v-bind:value="dealer.businessAddress" disabled>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="business-phone">Phone</label>
                        <input type="text" class="form-control" id="business-phone" placeholder="Phone Number" v-bind:value="dealer.phoneNumber" disabled>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="business-email">Email</label>
                        <input type="text" class="form-control" id="business-email" placeholder="Email address" v-bind:value="dealer.email" disabled>
                    </div>
                </div>
            </div>

            <div class="row text-center">
                <button class="btn btn-default"
                        style="margin-bottom: 0.5em"
                        @click.prevent="nextStep()">Next<i class="fa fa-arrow-right fa-fw"></i>
                </button>
            </div>

    </div>
</template>

<script type="text/babel">
    import DealerMap from './dealer-map/Map.vue'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import apiDealers from 'src/api/dealers'
    import validations from 'src/validations/customer-order-form/step-dealer'

    export default {
        name: 'step-dealer',
        mixins: [vuelidateAnyerror],
        components: {
            DealerMap
        },
        data() {
            return {
                showMap: false,
                allDealers: []
            }
        },
        props: {
            dealer: {
                type: Object,
                default() {
                    return {
                        id: null,
                        businessName: null,
                        businessAddress: null,
                        phoneNumber: null,
                        email: null,
                        taxRate: null,
                        depositType: null,
                        cashSaleDepositRate: null
                    }
                }
            }
        },
        created() {
            apiDealers.get({
                query: {
                    include: ['location'],
                    where: {
                        is_active: 'yes'
                    },
                    per_page: 9999
                }
            }).then((response) => {
                this.allDealers = response.data.data
                this.$emit('data-ready')
            })

            this.$on('update:dealer', (dealer) => {
                new Promise(resolve => {
                    resolve()
                }).then(() => {
                    this.nextStep()
                })
            })
        },
        computed: {
            dealers() {
                if (this.allDealers.length <= 0) return {}
                return _.keyBy(this.allDealers, 'id')
            }
        },
        methods: {
            selectDealer: function(id) {
                if (!id) return
                let foundDealer = _.find(this.dealers, {id: parseInt(id)})
                if (!foundDealer) return

                let dealer = {
                    id: foundDealer.id,
                    businessName: foundDealer.businessName,
                    businessAddress: foundDealer.location.address,
                    phoneNumber: foundDealer.phone,
                    email: foundDealer.email,
                    taxRate: foundDealer.taxRate,
                    depositType: foundDealer.depositType,
                    cashSaleDepositRate: foundDealer.cashSaleDepositRate
                }

                this.$emit('update:dealer', dealer)
            },
            nextStep() {
                this.$parent.nextStep('next')
            }
        },
        validations: validations
    }
</script>