<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.id">
                    <div class="col-xs-12 col-md-3">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="date_updated" class="control-label">Date Updated</label>
                        <div id="date_updated">
                            {{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                </div>

                <!-- common -->
                <div class="row col-xs-12">
                    <div class="col-xs-6 col-md-6">
                        <label for="first_name" class="control-label">First Name *</label>
                        <input type="text" class="form-control" id="first_name"
                               :class="{'invalid': $v.curItem.firstName.$error}"
                               :value="curItem.firstName"
                               v-model="curItem.firstName"
                               @blur="$v.curItem.firstName.$touch"
                               placeholder="First Name">
                        <div v-if="$v.curItem.firstName.$dirty && $v.curItem.firstName.name === false" class="alert alert-danger" role="alert">Enter a valid First Name.</div>
                        <div v-if="$v.curItem.firstName.$dirty && $v.curItem.firstName.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <label for="last_name" class="control-label">Last Name *</label>
                        <input type="text" id="last_name"
                               class="form-control"
                               :class="{'invalid': $v.curItem.lastName.$error}"
                               :value="curItem.lastName"
                               @blur="$v.curItem.lastName.$touch"
                               v-model="curItem.lastName"
                               placeholder="Last Name">
                        <div v-if="$v.curItem.lastName.$dirty && $v.curItem.lastName.required === false" class="alert alert-danger" role="alert">The Last Name is required.</div>
                        <div v-if="$v.curItem.lastName.$dirty && $v.curItem.lastName.name === false" class="alert alert-danger" role="alert">Enter correct Last Name.</div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-6 col-md-3">
                        <label for="state" class="control-label">Email *</label>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.curItem.email.$error}"
                               :value="curItem.email"
                               @blur="$v.curItem.email.$touch"
                               v-model="curItem.email"
                               placeholder="Email">
                        <div v-if="$v.curItem.email.$dirty && $v.curItem.email.required === false" class="alert alert-danger" role="alert">The Email is required.</div>
                        <div v-if="$v.curItem.email.$dirty && $v.curItem.email.email === false" class="alert alert-danger" role="alert">Enter correct Email.</div>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <label for="phone" class="control-label">Phone *</label>
                        <cleave :options='cleaveOptions.phone' class="form-control" id="phone"
                                :class="{'invalid': $v.curItem.phone.$error}"
                                :value="curItem.phone"
                                @blur="$v.curItem.phone.$touch"
                                v-model="curItem.phone"
                                placeholder="Phone #"
                                ref="customerPhoneNumber"/>
                        <div v-if="$v.curItem.phone.$dirty && $v.curItem.phone.required === false" class="alert alert-danger" role="alert">The Phone Number is required.</div>
                        <div v-if="$v.curItem.phone.$dirty && $v.curItem.phone.phone === false" class="alert alert-danger" role="alert">Enter correct Phone Number.</div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="address" class="control-label">Address *</label>
                        <input type="text" id="address"
                               class="form-control"
                               :class="{'invalid': $v.curItem.address.$error}"
                               :value="curItem.address"
                               @blur="$v.curItem.address.$touch"
                               v-model="curItem.address"
                               placeholder="Address">
                        <div v-if="$v.curItem.address.$dirty && $v.curItem.address.required === false" class="alert alert-danger" role="alert">The Address is required.</div>
                        <div v-if="$v.curItem.address.$dirty && $v.curItem.address.address === false" class="alert alert-danger" role="alert">Enter correct Address.</div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="city" class="control-label">City *</label>
                        <input type="text" id="city"
                               class="form-control"
                               :class="{'invalid': $v.curItem.city.$error}"
                               :value="curItem.city"
                               @blur="$v.curItem.city.$touch"
                               v-model="curItem.city"
                               placeholder="City">
                        <div v-if="$v.curItem.city.$dirty && $v.curItem.city.required === false" class="alert alert-danger" role="alert">The City is required.</div>
                        <div v-if="$v.curItem.city.$dirty && $v.curItem.city.geo === false" class="alert alert-danger" role="alert">Enter correct City.</div>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <label for="state" class="control-label">State *</label>
                        <input type="text" id="state"
                               class="form-control"
                               :class="{'invalid': $v.curItem.state.$error}"
                               :value="curItem.state"
                               @blur="$v.curItem.state.$touch"
                               v-model="curItem.state"
                               placeholder="State">
                        <div v-if="$v.curItem.state.$dirty && $v.curItem.state.required === false" class="alert alert-danger" role="alert">The State is required.</div>
                        <div v-if="$v.curItem.state.$dirty && $v.curItem.state.geo === false" class="alert alert-danger" role="alert">Enter correct State.</div>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <label for="zip" class="control-label">Zip *</label>
                        <cleave id="zip"
                                :options='cleaveOptions.zip'
                                class="form-control"
                                :class="{'invalid': $v.curItem.zip.$error}"
                                :value="curItem.zip"
                                @blur="$v.curItem.zip.$touch"
                                v-model="curItem.zip"
                                placeholder="Zip"
                                ref="customerZip"/>
                        <div v-if="$v.curItem.zip.$dirty && $v.curItem.zip.required === false" class="alert alert-danger" role="alert">The Zip is required.</div>
                        <div v-if="$v.curItem.zip.$dirty && $v.curItem.zip.zip === false" class="alert alert-danger" role="alert">Enter correct Zip.</div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import Cleave from 'vue-cleave/src/Cleave.vue'
    import 'cleave.js/dist/addons/cleave-phone.mx'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'
    import cleaveOptions from 'src/configs/cleave'

    import apiCustomers from 'src/api/customers'
    import validations from './validations'

    export default {
        name: 'customer-form',
        extends: BaseDataItem,
        mixins: [vuelidateAnyerror],
        validations: validations,
        components: {Cleave},
        data() {
            return {
                cleaveOptions,
                activeFlags: {},
                categories: {},
                curItem: {
                    firstName: null,
                    lastName: null,
                    email: null,
                    phone: null,
                    address: null,
                    city: null,
                    state: null,
                    zip: null
                }
            }
        },
        computed: {
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            }
        },
        methods: {
            save({ item, data }) {
                return apiCustomers.save({ item, data }).then(response => response.data)
            },
            submit() {
                this.$v.$touch()
                if (this.$anyerror) return

                let item = _.merge({}, {
                    firstName: this.curItem.firstName,
                    lastName: this.curItem.lastName,
                    email: this.curItem.email,
                    phone: this.curItem.phone,
                    address: this.curItem.address,
                    city: this.curItem.city,
                    state: this.curItem.state,
                    zip: this.curItem.zip
                })

                if (this.curItem.id) item.id = this.curItem.id

                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: item, data: form })
                    .then(data => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: data.msg
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.item.id) {
                    apiCustomers.get({
                        id: this.item.id,
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    this.initDependencies()
                        .then(response => { this.$emit('data-ready') })
                        .catch(response => { this.$emit('data-failed', response) })
                }
            },
            initDependencies() {
                const datas = []

                return Promise.all(datas)
                    .then(response => {
                        return response
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>