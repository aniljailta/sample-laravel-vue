<template>

    <div class="form">
        <div class="row">
            <div class="col-xs-12">
                <h4>Do you have a central dispatch for all your deliveries?</h4>
                <div class="btn-group btn-toggle input-buttons btn-group-sm">
                    <label :class="{'active': settings.deliveryDispatch === 'dispatch'}" class="btn btn-default">
                        <input @click="update('deliveryDispatch', 'dispatch')"
                               type="radio"
                               name="deliveryDispatch"
                               :checked="settings.deliveryDispatch === 'dispatch'"> Dispatch
                    </label>
                    <label :class="{'active': settings.deliveryDispatch === 'driver'}" class="btn btn-default">
                        <input @click="update('deliveryDispatch', 'driver')"
                               type="radio"
                               name="deliveryDispatch"
                               :checked="settings.deliveryDispatch === 'driver'"> Driver
                    </label>
                </div>
                <div v-if="$v.settings.deliveryDispatch.$dirty && $v.settings.deliveryDispatch.required === false" class="alert alert-danger" role="alert">This field is required.</div>
            </div>
        </div>
        <div v-if="settings.deliveryDispatch === 'driver'">
            <div class="row col-xs-12 col-md-4">
                <div class="alert alert-info">
                    Driver contact information will be indicated on all the delivery forms.
                </div>
            </div>
        </div>
        <div v-if="settings.deliveryDispatch === 'dispatch'">
            <div class="row col-xs-12 col-md-4">
                <div class="alert alert-info">
                    Dispatch information will be indicated on all the delivery forms.
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact Name *</label>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.deliveryContactName.$error}"
                               :value="settings.deliveryContactName"
                               @input="update('deliveryContactName', $event.target.value)"
                               @blur="$v.settings.deliveryContactName.$touch"
                               placeholder="Contact Name">
                        <div v-if="$v.settings.deliveryContactName.$dirty && $v.settings.deliveryContactName.required === false" class="alert alert-danger" role="alert">The contact name is required.</div>
                        <div v-if="$v.settings.deliveryContactName.$dirty && $v.settings.deliveryContactName.name === false" class="alert alert-danger" role="alert">Enter correct contact name.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact Phone # *</label>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.deliveryContactPhone.$error}"
                               :value="settings.deliveryContactPhone"
                               @input="update('deliveryContactPhone', $event.target.value)"
                               @blur="$v.settings.deliveryContactPhone.$touch"
                               placeholder="Contact Phone">
                        <div v-if="$v.settings.deliveryContactPhone.$dirty && $v.settings.deliveryContactPhone.required === false" class="alert alert-danger" role="alert">The phone is required.</div>
                        <div v-if="$v.settings.deliveryContactPhone.$dirty && $v.settings.deliveryContactPhone.phone === false" class="alert alert-danger" role="alert">Enter correct phone.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact Email *</label>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.deliveryContactEmail.$error}"
                               :value="settings.deliveryContactEmail"
                               @input="update('deliveryContactEmail', $event.target.value)"
                               @blur="$v.settings.deliveryContactEmail.$touch"
                               placeholder="Contact Email">
                        <div v-if="$v.settings.deliveryContactEmail.$dirty && $v.settings.deliveryContactEmail.required === false" class="alert alert-danger" role="alert">The email is required.</div>
                        <div v-if="$v.settings.deliveryContactEmail.$dirty && $v.settings.deliveryContactEmail.email === false" class="alert alert-danger" role="alert">Enter correct email.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validation from './validations/delivery-info'

    export default {
        name: 'settings-delivery-info',
        mixins: [vuelidateAnyerror],
        validations: validation,
        props: {
            settings: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {}
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$emit('update:validDelivery', !this.$anyerror)
            })

            this.$watch('settings.deliveryDispatch', (value) => {
                if (value === 'driver') {
                    this.$emit('update:settings',
                        {
                            ...this.settings,
                            ...{
                                deliveryContactName: null,
                                deliveryContactPhone: null,
                                deliveryContactEmail: null
                            }
                        })

                    this.$nextTick(() => {
                        this.$v.settings.$touch()
                    })
                }
            })
        },
        computed: {
        },
        methods: {
            update(path, val) {
                let settings = _.cloneDeep(this.settings)
                this.$emit('update:settings', _.set(settings, path, val))
                if (this.$v.settings[path]) {
                    this.$v.settings[path].$touch()
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>