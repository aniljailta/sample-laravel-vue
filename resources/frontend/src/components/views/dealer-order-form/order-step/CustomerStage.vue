<template>

    <div>
        <div class="col-xs-12 panel panel-default">
            <div class="form">
                <!-- Common specific -->
                <h3>
                    <u>CUSTOMER INFORMATION</u>
                </h3>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="lerning-about-us">How did this customer hear about us?</label>
                            <select class="form-control"
                                    :disabled="editionBlocked"
                                    v-bind:class="{ 'invalid': $v.customer.learningAboutUs.$error }"
                                    id="lerning-about-us"
                                    v-bind:value="customer.learningAboutUs"
                                    @blur="$v.customer.learningAboutUs.$touch"
                                    @change.prevent="updateCustomer({'learningAboutUs': $event.target.value})">
                                <option value="" selected="selected" disabled>Select...</option>
                                <option v-for="(learning, learning_index) in learningAboutUsVariants" v-bind:value="learning.id">{{ learning.title }}</option>
                            </select>
                            <div v-if="$v.customer.learningAboutUs.$dirty && $v.customer.learningAboutUs.required === false" class="alert alert-danger">This field is required.</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12" v-if="customer.learningAboutUs === 'other'">
                        <div class="form-group">
                            <label for="sales-person">* other</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   v-bind:class="{ 'invalid': $v.customer.learningAboutUsOther.$error }"
                                   name="Sales Person"
                                   id="sales-person"
                                   placeholder="How did this customer hear about us?"
                                   v-bind:value="customer.learningAboutUsOther"
                                   @blur="$v.customer.learningAboutUsOther.$touch"
                                   @input="updateCustomer({'learningAboutUsOther': $event.target.value})">
                            <div v-if="$v.customer.learningAboutUsOther.$dirty && $v.customer.learningAboutUsOther.required === false" class="alert alert-danger">This field is required.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name *</label>
                            <input type="text" class="form-control"
                                   :disabled="editionBlocked"
                                   :class="{'invalid': $v.customer.firstName.$error}"
                                   :value="customer.firstName"
                                   @input="updateCustomer({'firstName': $event.target.value})"
                                   @blur="$v.customer.firstName.$touch"
                                   placeholder="First Name">
                            <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.name === false" class="alert alert-danger" role="alert">Enter a valid First Name.</div>
                            <div v-if="$v.customer.firstName.$dirty && $v.customer.firstName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.lastName.$error}"
                                   :value="customer.lastName"
                                   @blur="$v.customer.lastName.$touch"
                                   @input="updateCustomer({'lastName': $event.target.value})"
                                   placeholder="Last Name">
                            <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.required === false" class="alert alert-danger" role="alert">The Last Name is required.</div>
                            <div v-if="$v.customer.lastName.$dirty && $v.customer.lastName.name === false" class="alert alert-danger" role="alert">Enter correct Last Name.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label>Email *</label>
                        <input type="text"
                               :disabled="editionBlocked"
                               class="form-control"
                               :class="{'invalid': $v.customer.email.$error}"
                               :value="customer.email"
                               @blur="$v.customer.email.$touch"
                               @input="updateCustomer({'email': $event.target.value})"
                               placeholder="Email">
                        <div v-if="$v.customer.email.$dirty && $v.customer.email.required === false" class="alert alert-danger" role="alert">The Email is required.</div>
                        <div v-if="$v.customer.email.$dirty && $v.customer.email.email === false" class="alert alert-danger" role="alert">Enter correct Email.</div>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone # *</label>
                            <Cleave :disabled="editionBlocked"
                                    :options='cleaveOptions' class="form-control"
                                    :class="{'invalid': $v.customer.phoneNumber.$error}"
                                    :value="customer.phoneNumber"
                                    @blur="$v.customer.phoneNumber.$touch"
                                    @input="updateCustomer({'phoneNumber': $event})"
                                    placeholder="Phone #"
                                    ref="customerPhoneNumber"/>
                            <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.required === false" class="alert alert-danger" role="alert">The Phone Number is required.</div>
                            <div v-if="$v.customer.phoneNumber.$dirty && $v.customer.phoneNumber.phone === false" class="alert alert-danger" role="alert">Enter correct Phone Number.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.address.$error}"
                                   :value="customer.address"
                                   @blur="$v.customer.address.$touch"
                                   @input="updateCustomer({'address': $event.target.value})"
                                   placeholder="Address">
                            <div v-if="$v.customer.address.$dirty && $v.customer.address.required === false" class="alert alert-danger" role="alert">The Address is required.</div>
                            <div v-if="$v.customer.address.$dirty && $v.customer.address.address === false" class="alert alert-danger" role="alert">Enter correct Address.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.city.$error}"
                                   :value="customer.city"
                                   @blur="$v.customer.city.$touch"
                                   @input="updateCustomer({'city': $event.target.value})"
                                   placeholder="City">
                            <div v-if="$v.customer.city.$dirty && $v.customer.city.required === false" class="alert alert-danger" role="alert">The City is required.</div>
                            <div v-if="$v.customer.city.$dirty && $v.customer.city.geo === false" class="alert alert-danger" role="alert">Enter correct City.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.state.$error}"
                                   :value="customer.state"
                                   @blur="$v.customer.state.$touch"
                                   @input="updateCustomer({'state': $event.target.value})"
                                   placeholder="State">
                            <div v-if="$v.customer.state.$dirty && $v.customer.state.required === false" class="alert alert-danger" role="alert">The State is required.</div>
                            <div v-if="$v.customer.state.$dirty && $v.customer.state.geo === false" class="alert alert-danger" role="alert">Enter correct State.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zip *</label>
                            <Cleave :disabled="editionBlocked"
                                    :options='cleaveOptionsZip'
                                    class="form-control"
                                    :class="{'invalid': $v.customer.zip.$error}"
                                    :value="customer.zip"
                                    @blur="$v.customer.zip.$touch"
                                    @input="updateCustomer({'zip': $event})"
                                    placeholder="Zip"
                                    ref="customerZip"/>
                            <div v-if="$v.customer.zip.$dirty && $v.customer.zip.required === false" class="alert alert-danger" role="alert">The Zip is required.</div>
                            <div v-if="$v.customer.zip.$dirty && $v.customer.zip.zip === false" class="alert alert-danger" role="alert">Enter correct Zip.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Building Location</label>
                        <div style="margin-top: 0px">
                            <label>
                                <input type="checkbox"
                                       :disabled="editionBlocked"
                                       :checked="customer.buildingInSameAddress"
                                       @change="updateCustomer({'buildingInSameAddress': $event.target.checked})">
                                Building will be located at same address as above
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="customer.buildingInSameAddress === false">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationAddress.$error}"
                                   :value="customer.buildingLocationAddress"
                                   @blur="$v.customer.buildingLocationAddress.$touch"
                                   @input="updateCustomer({'buildingLocationAddress': $event.target.value})"
                                   placeholder="Address">
                            <div v-if="$v.customer.buildingLocationAddress.$dirty && $v.customer.buildingLocationAddress.required === false" class="alert alert-danger" role="alert">The Address is required.</div>
                            <div v-if="$v.customer.buildingLocationAddress.$dirty && $v.customer.buildingLocationAddress.address === false" class="alert alert-danger" role="alert">Enter correct Address.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationCity.$error}"
                                   :value="customer.buildingLocationCity"
                                   @blur="$v.customer.buildingLocationCity.$touch"
                                   @input="updateCustomer({'buildingLocationCity': $event.target.value})"
                                   placeholder="City">
                            <div v-if="$v.customer.buildingLocationCity.$dirty && $v.customer.buildingLocationCity.required === false" class="alert alert-danger" role="alert">The City is required.</div>
                            <div v-if="$v.customer.buildingLocationCity.$dirty && $v.customer.buildingLocationCity.geo === false" class="alert alert-danger" role="alert">Enter correct City.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State *</label>
                            <input type="text"
                                   :disabled="editionBlocked"
                                   class="form-control"
                                   :class="{'invalid': $v.customer.buildingLocationState.$error}"
                                   :value="customer.buildingLocationState"
                                   @blur="$v.customer.buildingLocationState.$touch"
                                   @input="updateCustomer({'buildingLocationState': $event.target.value})"
                                   placeholder="State">
                            <div v-if="$v.customer.buildingLocationState.$dirty && $v.customer.buildingLocationState.required === false" class="alert alert-danger" role="alert">The State is required.</div>
                            <div v-if="$v.customer.buildingLocationState.$dirty && $v.customer.buildingLocationState.geo === false" class="alert alert-danger" role="alert">Enter correct State.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zip *</label>
                            <Cleave :disabled="editionBlocked"
                                    :options='cleaveOptionsZip'
                                    class="form-control"
                                    :class="{'invalid': $v.customer.buildingLocationZip.$error}"
                                    :value="customer.buildingLocationZip"
                                    @blur="$v.customer.buildingLocationZip.$touch"
                                    @input="updateCustomer({'buildingLocationZip': $event})"
                                    placeholder="Zip"
                                    ref="customerBuildingLocationZip"/>
                            <div v-if="$v.customer.buildingLocationZip.$dirty && $v.customer.buildingLocationZip.required === false" class="alert alert-danger" role="alert">The Zip is required.</div>
                            <div v-if="$v.customer.buildingLocationZip.$dirty && $v.customer.buildingLocationZip.zip === false" class="alert alert-danger" role="alert">Enter correct Zip.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prev/Next buttons -->
            <div class="row" style="margin-bottom: 1em;">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-default" v-on:click.prevent="goToStep('previous')"><i
                            class="fa fa-arrow-left fa-fw"></i>Previous
                    </button>
                    <button type="button" class="btn btn-default" v-on:click.prevent="goToStep('next')">Next<i
                            class="fa fa-arrow-right fa-fw"></i></button>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import customerStageValidation from 'src/validations/dealer-order-form/customer-stage.validation.js'
    import apiOrderReferences from 'src/api/order-references'
    import Cleave from 'vue-cleave/src/Cleave.vue'
    import 'cleave.js/dist/addons/cleave-phone.mx'

    export default {
        name: 'customer-stage',
        mixins: [vuelidateAnyerror],
        components: {Cleave},
        validations: customerStageValidation,
        data() {
            return {
                learningAboutUsVariants: {},
                formatedValue: null,
                cleaveOptions: {
                    delimiter: '-',
                    blocks: [3, 3, 4],
                    numericOnly: true
                },
                cleaveOptionsZip: {
                    blocks: [5],
                    numericOnly: true
                }
            }
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$parent.revalidate()
            })

            this.$bus.$on('dofOrderLoaded', () => {
                this.$refs.customerPhoneNumber.cleave.setRawValue(this.customer.phoneNumber)
                this.$refs.customerZip.cleave.setRawValue(this.customer.zip)

                if (this.$refs.customerBuildingLocationZip) {
                    this.$refs.customerBuildingLocationZip.cleave.setRawValue(this.customer.buildingLocationZip)
                }
            })

            apiOrderReferences.learningAboutUs({}).then((response) => {
                this.learningAboutUsVariants = response.data
            })
        },
        computed: {
            ...mapGetters({
                customer: 'dealerOrderForm/orderCustomer',
                orderStateMode: 'dealerOrderForm/orderStateMode',
                orderPaymentType: 'dealerOrderForm/orderPaymentType',
                editionBlocked: 'dealerOrderForm/editionBlocked'
            })
        },
        methods: {
            ...mapActions({
                updateOrderCustomer: 'dealerOrderForm/updateOrderCustomer'
            }),
            updateCustomer(object) {
                this.updateOrderCustomer(object)

                _.each(object, (val, key) => {
                    if (this.$v[key]) this.$v[key].$touch()
                })
            },
            goToStep(step, options) {
                return this.$parent.goToStep(step, options)
            }
        }
    }
</script>

<style type="text/css">
    .form-group {
        margin-bottom: 15px!important;
    }
</style>