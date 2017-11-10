<template>

    <div class="form">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.name.$error}"
                           :value="settings.name"
                           @input="update('name', $event.target.value)"
                           @blur="$v.settings.name.$touch"
                           placeholder="Company Name">
                    <div v-if="$v.settings.name.$dirty && $v.settings.name.required === false" class="alert alert-danger" role="alert">The name is required.</div>
                    <div v-if="$v.settings.name.$dirty && $v.settings.name.name === false" class="alert alert-danger" role="alert">Enter correct name.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Address *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.address.$error}"
                           :value="settings.address"
                           @input="update('address', $event.target.value)"
                           @blur="$v.settings.address.$touch"
                           placeholder="Company Address">
                    <div v-if="$v.settings.address.$dirty && $v.settings.address.required === false" class="alert alert-danger" role="alert">The address is required.</div>
                    <div v-if="$v.settings.address.$dirty && $v.settings.address.address === false" class="alert alert-danger" role="alert">Enter correct address.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>City *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.city.$error}"
                           :value="settings.city"
                           @input="update('city', $event.target.value)"
                           @blur="$v.settings.city.$touch"
                           placeholder="Company City">
                    <div v-if="$v.settings.city.$dirty && $v.settings.city.required === false" class="alert alert-danger" role="alert">The city is required.</div>
                    <div v-if="$v.settings.city.$dirty && $v.settings.city.geo === false" class="alert alert-danger" role="alert">Enter correct city.</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>State *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.state.$error}"
                           :value="settings.state"
                           @input="update('state', $event.target.value)"
                           @blur="$v.settings.state.$touch"
                           placeholder="Company State">
                    <div v-if="$v.settings.state.$dirty && $v.settings.state.required === false" class="alert alert-danger" role="alert">The state is required.</div>
                    <div v-if="$v.settings.state.$dirty && $v.settings.state.geo === false" class="alert alert-danger" role="alert">Enter correct state.</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Zip *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.zip.$error}"
                           :value="settings.zip"
                           @input="update('zip', $event.target.value)"
                           @blur="$v.settings.zip.$touch"
                           placeholder="Company Zip">
                    <div v-if="$v.settings.zip.$dirty && $v.settings.zip.required === false" class="alert alert-danger" role="alert">The zip is required.</div>
                    <div v-if="$v.settings.zip.$dirty && $v.settings.zip.zip === false" class="alert alert-danger" role="alert">Enter correct zip.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Phone # *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.phone.$error}"
                           :value="settings.phone"
                           @input="update('phone', $event.target.value)"
                           @blur="$v.settings.phone.$touch"
                           placeholder="Company Phone">
                    <div v-if="$v.settings.phone.$dirty && $v.settings.phone.required === false" class="alert alert-danger" role="alert">The phone is required.</div>
                    <div v-if="$v.settings.phone.$dirty && $v.settings.phone.phone === false" class="alert alert-danger" role="alert">Enter correct phone.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.email.$error}"
                           :value="settings.email"
                           @input="update('email', $event.target.value)"
                           @blur="$v.settings.email.$touch"
                           placeholder="Company Email">
                    <div v-if="$v.settings.email.$dirty && $v.settings.email.required === false" class="alert alert-danger" role="alert">The email is required.</div>
                    <div v-if="$v.settings.email.$dirty && $v.settings.email.email === false" class="alert alert-danger" role="alert">Enter correct email.</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <select class="form-control" id="timezone" name="timezone"
                            :class="{ 'invalid': $v.settings.timeZone.$error }"
                            :value="settings.timeZone"
                            @blur="$v.settings.timeZone.$touch"
                            @change.prevent="update('timeZone', $event.target.value)">
                        <option v-for="(timeZone, indexId) in timezones" v-bind:value="timeZone">{{ timeZone }}</option>
                    </select>
                    <div v-if="$v.settings.timeZone.$dirty && $v.settings.timeZone.required === false" class="alert alert-danger">This field is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Default Row Count</label>
                    <input type="number"
                           class="form-control"
                           :class="{'invalid': $v.settings.perPage.$error}"
                           :value="settings.perPage"
                           @input="update('perPage', $event.target.value)"
                           @blur="$v.settings.perPage.$touch"
                           placeholder="Default Row Count">
                    <div v-if="$v.settings.perPage.$dirty && $v.settings.perPage.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                </div>
            </div>
        </div>

        <h5>Email settings *
            <small>
                <span @click="showEmailSettingsHelp = !showEmailSettingsHelp" class="pointer black">
                    <i class="fa fa-question-circle" aria-hidden="true"></i> <span class="dotted">Help</span>
                </span>
            </small>
        </h5>
        <collapse v-model="showEmailSettingsHelp">
            <div class="row">
                <div class="col-xs-12">
                    <dl>
                        <dt>Gmail</dt>
                        <dd>
                            <a href="https://support.google.com/a/answer/176600?hl=en"
                               target="_blank">https://support.google.com/a/answer/176600?hl=en
                            </a>
                        </dd>
                    </dl>
                    <dl>
                        <dt>Microsoft Office 365</dt>
                        <dd>
                            <a href="https://support.office.com/en-us/article/How-to-set-up-a-multifunction-device-or-application-to-send-email-using-Office-365-69f58e99-c550-4274-ad18-c805d654b4c4"
                               target="_blank">
                                https://support.office.com/en-us/article/How-to-set-up-a-multifunction-device-or-application-to-send-email-using-Office-365-69f58e99-c550-4274-ad18-c805d654b4c4
                            </a>
                        </dd>
                    </dl>
                    <dl>
                        <dt>Hotmail</dt>
                        <dd>
                            <a href="https://help.yahoo.com/kb/SLN4724.html"
                               target="_blank">
                                https://help.yahoo.com/kb/SLN4724.html
                            </a> (smtp settings)
                        </dd>
                        <dd>
                            <a href="https://help.yahoo.com/kb/generate-third-party-passwords-sln15241.html"
                               target="_blank">
                                https://help.yahoo.com/kb/generate-third-party-passwords-sln15241.html
                            </a> (app password)
                        </dd>
                    </dl>
                </div>
            </div>
        </collapse>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Host</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailHost.$error}"
                           :value="settings.mailHost"
                           @input="update('mailHost', $event.target.value)"
                           @blur="$v.settings.mailHost.$touch"
                           placeholder="Host">
                    <div v-if="$v.settings.mailHost.$dirty && $v.settings.mailHost.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Port</label>
                    <input type="number"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailPort.$error}"
                           :value="settings.mailPort"
                           @input="update('mailPort', $event.target.value)"
                           @blur="$v.settings.mailPort.$touch"
                           placeholder="Host">
                    <div v-if="$v.settings.mailPort.$dirty && $v.settings.mailPort.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                    <div v-if="$v.settings.mailPort.$dirty && $v.settings.mailPort.numeric === false" class="alert alert-danger" role="alert">This field should be numeric..</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailUsername.$error}"
                           :value="settings.mailUsername"
                           @input="update('mailUsername', $event.target.value)"
                           @blur="$v.settings.mailUsername.$touch"
                           placeholder="Username">
                    <div v-if="$v.settings.mailUsername.$dirty && $v.settings.mailUsername.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        From
                        <small class="black">
                            <tooltip text="Email address for return purposes">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </tooltip>
                        </small>
                    </label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailFrom.$error}"
                           :value="settings.mailFrom"
                           @input="update('mailFrom', $event.target.value)"
                           @blur="$v.settings.mailFrom.$touch"
                           placeholder="From">
                    <div v-if="$v.settings.mailFrom.$dirty && $v.settings.mailFrom.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                    <div v-if="$v.settings.mailFrom.$dirty && $v.settings.mailFrom.email === false" class="alert alert-danger" role="alert">Enter correct email.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>
                        Password
                        <small class="black">
                            <tooltip text="Account password or app passoword">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </tooltip>
                        </small>
                    </label>
                    <input type="password"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailPassword.$error}"
                           :value="settings.mailPassword"
                           @input="update('mailPassword', $event.target.value)"
                           @blur="$v.settings.mailPassword.$touch"
                           placeholder="Password">
                    <div v-if="$v.settings.mailPassword.$dirty && $v.settings.mailPassword.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Encryption
                        <small class="black">
                            <tooltip text="TLS, SSL or empty">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </tooltip>
                        </small>
                    </label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.mailEncryption.$error}"
                           :value="settings.mailEncryption"
                           @input="update('mailEncryption', $event.target.value)"
                           @blur="$v.settings.mailEncryption.$touch"
                           placeholder="Encryption">
                    <div v-if="$v.settings.mailEncryption.$dirty && $v.settings.mailEncryption.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-primary" @click="showTestEmailModal = true">Test email</button>
            </div>
        </div>

        <modal-test-email v-if="showTestEmailModal" :show="showTestEmailModal" v-on:close="showTestEmailModal = false"/>

        <br/>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>
                        Website
                        <small class="black">
                            <tooltip text="Full adress like https://domain.com">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </tooltip>
                        </small>
                    </label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.website.$error}"
                           :value="settings.website"
                           @input="update('website', $event.target.value)"
                           @blur="$v.settings.website.$touch"
                           placeholder="Company Website">
                    <div v-if="$v.settings.website.$dirty && $v.settings.website.required === false" class="alert alert-danger" role="alert">The website is required.</div>
                    <div v-if="$v.settings.website.$dirty && $v.settings.website.url === false" class="alert alert-danger" role="alert">Enter correct URL.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Facebook</label>
                    <div class="input-group input-group">
                        <div class="input-group-addon">https://www.facebook.com/</div>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.facebook.$error}"
                               :value="settings.facebook"
                               @input="update('facebook', $event.target.value)"
                               @blur="$v.settings.facebook.$touch"
                               placeholder="ID">
                    </div>
                    <div v-if="$v.settings.facebook.$dirty && $v.settings.facebook.required === false" class="alert alert-danger" role="alert">The facebook account is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Instagram</label>
                    <div class="input-group input-group">
                        <div class="input-group-addon">https://www.instagram.com/</div>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.instagram.$error}"
                               :value="settings.instagram"
                               @input="update('instagram', $event.target.value)"
                               @blur="$v.settings.instagram.$touch"
                               placeholder="ID">
                    </div>
                    <div v-if="$v.settings.instagram.$dirty && $v.settings.instagram.required === false" class="alert alert-danger" role="alert">The instagram account is required.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Pinterest</label>
                    <div class="input-group input-group">
                        <div class="input-group-addon">https://www.pinterest.com/</div>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.pinterest.$error}"
                               :value="settings.pinterest"
                               @input="update('pinterest', $event.target.value)"
                               @blur="$v.settings.pinterest.$touch"
                               placeholder="ID">
                    </div>
                    <div v-if="$v.settings.pinterest.$dirty && $v.settings.pinterest.required === false" class="alert alert-danger" role="alert">The pintrest account is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>G+</label>
                    <div class="input-group input-group">
                        <div class="input-group-addon">https://plus.google.com/</div>
                        <input type="text"
                               class="form-control"
                               :class="{'invalid': $v.settings.gplus.$error}"
                               :value="settings.gplus"
                               @input="update('gplus', $event.target.value)"
                               @blur="$v.settings.gplus.$touch"
                               placeholder="ID">
                    </div>
                    <div v-if="$v.settings.gplus.$dirty && $v.settings.gplus.required === false" class="alert alert-danger" role="alert">The google plus account is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-3">
                <div class="form-group">
                    <label>Logo *</label>
                    <logo class="company-logo__container"
                          :attachment="logo"
                          @add-attachment="updateCompanyLogo"
                          @remove-attachemnt="updateCompanyLogo"/>
                    <div v-if="$v.settings.logo.$dirty && $v.settings.logo.required === false" class="alert alert-danger" role="alert">The company logo is required.</div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapActions} from 'vuex'
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validation from './validations/company-info'
    import {Collapse, Tooltip} from 'uiv/src/components'
    import ModalTestEmail from '../modals/ModalSendEmail.vue'
    import Logo from './company-info/Logo.vue'

    import apiSettings from 'src/api/settings'

    export default {
        name: 'settings-company-info',
        mixins: [vuelidateAnyerror],
        components: {Logo, Tooltip, Collapse, ModalTestEmail},
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
            return {
                timezones: [],
                showEmailSettingsHelp: false,
                showTestEmailModal: false
            }
        },
        created() {
            this.getTimezones()
            this.$watch('$anyerror', (value) => {
                this.$emit('update:validCompany', !this.$anyerror)
            })
        },
        computed: {
            logo() {
                let attachment = {
                    canDelete: false,
                    canUpload: true,
                    categoryId: 'company_logo',
                    files: []
                }

                if (this.settings.logo) {
                    attachment.files = [this.settings.logo]
                }

                return attachment
            }
        },
        methods: {
            ...mapActions({
                updateLogo: 'settings/updateLogo'
            }),
            update(path, val) {
                let settings = _.cloneDeep(this.settings)
                this.$emit('update:settings', _.set(settings, path, val))
                if (this.$v.settings[path]) {
                    this.$v.settings[path].$touch()
                }
            },
            updateCompanyLogo(item) {
                this.update('logo', item)
                this.updateLogo(item)
            },
            getTimezones() {
                return apiSettings.timezones().then((response) => {
                    this.timezones = response.data
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>