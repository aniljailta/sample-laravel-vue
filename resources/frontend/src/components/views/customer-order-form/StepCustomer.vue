<template>

        <div class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text"
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.firstName.$error}"
                               v-bind:value="customer.firstName"
                               @input="update('firstName', $event.target.value)"
                               @blur="$v.customer.firstName.$touch"
                               placeholder="First Name">
                        <div v-if="$v.customer.firstName.$dirty && !$v.customer.firstName.required"
                             class="alert alert-danger"
                             role="alert">The First Name is required.</div>
                        <div v-if="$v.customer.firstName.$dirty && !$v.customer.firstName.name"
                             class="alert alert-danger"
                             role="alert">Enter correct First Name.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.lastName.$error}"
                               v-bind:value="customer.lastName"
                               @blur="$v.customer.lastName.$touch"
                               @input="update('lastName', $event.target.value)" 
                               placeholder="Last Name">
                        <div v-if="$v.customer.lastName.$dirty && !$v.customer.lastName.required"
                             class="alert alert-danger"
                             role="alert">The Last Name is required.</div>
                        <div v-if="$v.customer.lastName.$dirty && !$v.customer.lastName.name"
                             class="alert alert-danger"
                             role="alert">Enter correct Last Name.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.email.$error}"
                               v-bind:value="customer.email"
                               @blur="$v.customer.email.$touch"
                               @input="update('email', $event.target.value)" 
                               placeholder="Email">
                        <div v-if="$v.customer.email.$dirty && !$v.customer.email.required"
                             class="alert alert-danger"
                             role="alert">The Email is required.</div>
                        <div v-if="$v.customer.email.$dirty && !$v.customer.email.email"
                             class="alert alert-danger"
                             role="alert">Enter correct Email.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone # *</label>

                        <Cleave :options='cleaveOptions' class="form-control"
                            :class="{'invalid': $v.customer.phoneNumber.$error}"
                            :value="customer.phoneNumber"
                            @blur="$v.customer.phoneNumber.$touch"
                            @input="update('phoneNumber', $event)"
                            placeholder="Phone #"></Cleave>

                        <div v-if="$v.customer.phoneNumber.$dirty && !$v.customer.phoneNumber.required"
                             class="alert alert-danger"
                             role="alert">The Phone Number is required.</div>
                        <div v-if="$v.customer.phoneNumber.$dirty && !$v.customer.phoneNumber.phone"
                             class="alert alert-danger"
                             role="alert">Enter correct Phone Number.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.address.$error}"
                               v-bind:value="customer.address"
                               @blur="$v.customer.address.$touch"
                               @input="update('address', $event.target.value)" 
                               placeholder="Address">
                        <div v-if="$v.customer.address.$dirty && !$v.customer.address.required"
                             class="alert alert-danger"
                             role="alert">The Address is required.</div>
                        <div v-if="$v.customer.address.$dirty && !$v.customer.address.address"
                             class="alert alert-danger"
                             role="alert">Enter correct Address.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.city.$error}"
                               v-bind:value="customer.city"
                               @blur="$v.customer.city.$touch"
                               @input="update('city', $event.target.value)" 
                               placeholder="City">
                        <div v-if="$v.customer.city.$dirty && !$v.customer.city.required"
                             class="alert alert-danger"
                             role="alert">The City is required.</div>
                        <div v-if="$v.customer.city.$dirty && !$v.customer.city.geo"
                             class="alert alert-danger"
                             role="alert">Enter correct City.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>State *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.state.$error}"
                               v-bind:value="customer.state"
                               @blur="$v.customer.state.$touch"
                               @input="update('state', $event.target.value)" 
                               placeholder="State">
                        <div v-if="$v.customer.state.$dirty && !$v.customer.state.required"
                             class="alert alert-danger"
                             role="alert">The State is required.</div>
                        <div v-if="$v.customer.state.$dirty && !$v.customer.state.geo"
                             class="alert alert-danger"
                             role="alert">Enter correct State.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Zip *</label>
                        <Cleave :options='cleaveOptionsZip'
                            class="form-control"
                            :class="{'invalid': $v.customer.zip.$error}"
                            :value="customer.zip"
                            @blur="$v.customer.zip.$touch"
                            @input="update('zip', $event)"
                            placeholder="Zip"></Cleave>
                        <div v-if="$v.customer.zip.$dirty && !$v.customer.zip.required"
                             class="alert alert-danger"
                             role="alert">The Zip is required.</div>
                        <div v-if="$v.customer.zip.$dirty && !$v.customer.zip.zip"
                             class="alert alert-danger"
                             role="alert">Enter correct Zip.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   v-bind:value="customer.buildingInSameAddress" 
                                   @change="update('buildingInSameAddress', $event.target.checked)">
                            Building will be located at same address as above
                        </label>
                    </div>
                </div>
            </div>
            <div class="row" v-if="customer.buildingInSameAddress == false">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.buildingLocationAddress.$error}"
                               v-bind:value="customer.buildingLocationAddress"
                               @blur="$v.customer.buildingLocationAddress.$touch"
                               @input="update('buildingLocationAddress', $event.target.value)" 
                               placeholder="Address">
                        <div v-if="$v.customer.buildingLocationAddress.$dirty && !$v.customer.buildingLocationAddress.required"
                             class="alert alert-danger"
                             role="alert">The Address is required.</div>
                        <div v-if="$v.customer.buildingLocationAddress.$dirty && !$v.customer.buildingLocationAddress.address"
                             class="alert alert-danger"
                             role="alert">Enter correct Address.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.buildingLocationCity.$error}"
                               v-bind:value="customer.buildingLocationCity"
                               @blur="$v.customer.buildingLocationCity.$touch"
                               @input="update('buildingLocationCity', $event.target.value)" 
                               placeholder="City">
                        <div v-if="$v.customer.buildingLocationCity.$dirty && !$v.customer.buildingLocationCity.required"
                             class="alert alert-danger"
                             role="alert">The City is required.</div>
                        <div v-if="$v.customer.buildingLocationCity.$dirty && !$v.customer.buildingLocationCity.geo"
                             class="alert alert-danger"
                             role="alert">Enter correct City.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>State *</label>
                        <input type="text" 
                               class="form-control"
                               v-bind:class="{'invalid': $v.customer.buildingLocationState.$error}"
                               v-bind:value="customer.buildingLocationState"
                               @blur="$v.customer.buildingLocationState.$touch"
                               @input="update('buildingLocationState', $event.target.value)" 
                               placeholder="State">
                        <div v-if="$v.customer.buildingLocationState.$dirty && !$v.customer.buildingLocationState.required"
                             class="alert alert-danger"
                             role="alert">The State is required.</div>
                        <div v-if="$v.customer.buildingLocationState.$dirty && !$v.customer.buildingLocationState.geo"
                             class="alert alert-danger"
                             role="alert">Enter correct State.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Zip *</label>
                        <Cleave :options='cleaveOptionsZip'
                            class="form-control"
                            :class="{'invalid': $v.customer.buildingLocationZip.$error}"
                            :value="customer.buildingLocationZip"
                            @blur="$v.customer.buildingLocationZip.$touch"
                            @input="update('buildingLocationZip', $event)"
                            placeholder="Zip"></Cleave>
                        <div v-if="$v.customer.buildingLocationZip.$dirty && !$v.customer.buildingLocationZip.required"
                             class="alert alert-danger"
                             role="alert">The Zip is required.</div>
                        <div v-if="$v.customer.buildingLocationZip.$dirty && !$v.customer.buildingLocationZip.zip"
                             class="alert alert-danger"
                             role="alert">Enter correct Zip.</div>
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
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validationStepCustomerInfo from 'src/validations/customer-order-form/step-customer-info'
    import Cleave from 'vue-cleave/src/Cleave.vue'
    import 'cleave.js/dist/addons/cleave-phone.mx'

    export default {
        name: 'step-customer',
        mixins: [vuelidateAnyerror],
        components: {Cleave},
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
        props: {
            customer: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        methods: {
            update(path, val) {
                let customer = _.cloneDeep(this.customer)
                this.$emit('update:customer', _.set(customer, path, val))
                if (this.$v.customer[path]) {
                    this.$v.customer[path].$touch()
                }
            },
            nextStep() {
                this.$parent.nextStep('next')
            }
        },
        validations: validationStepCustomerInfo
    }
</script>