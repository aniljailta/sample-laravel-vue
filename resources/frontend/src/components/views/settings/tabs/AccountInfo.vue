<template>

    <div class="form">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.firstName.$error}"
                           :value="settings.firstName"
                           @input="update('firstName', $event.target.value)"
                           @blur="$v.settings.firstName.$touch"
                           placeholder="Boss First Name">
                    <div v-if="$v.settings.firstName.$dirty && $v.settings.firstName.required === false" class="alert alert-danger" role="alert">The first name is required.</div>
                    <div v-if="$v.settings.firstName.$dirty && $v.settings.firstName.name === false" class="alert alert-danger" role="alert">Enter correct first name.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.lastName.$error}"
                           :value="settings.lastName"
                           @input="update('lastName', $event.target.value)"
                           @blur="$v.settings.lastName.$touch"
                           placeholder="Boss Last Name">
                    <div v-if="$v.settings.lastName.$dirty && $v.settings.lastName.required === false" class="alert alert-danger" role="alert">The last name is required.</div>
                    <div v-if="$v.settings.lastName.$dirty && $v.settings.lastName.name === false" class="alert alert-danger" role="alert">Enter correct last name.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.title.$error}"
                           :value="settings.title"
                           @input="update('title', $event.target.value)"
                           @blur="$v.settings.title.$touch"
                           placeholder="Boss Title">
                    <div v-if="$v.settings.title.$dirty && $v.settings.title.required === false" class="alert alert-danger" role="alert">The title is required.</div>
                    <div v-if="$v.settings.title.$dirty && $v.settings.title.name === false" class="alert alert-danger" role="alert">Enter correct title.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="text"
                           class="form-control"
                           :class="{'invalid': $v.settings.email.$error}"
                           :value="settings.email"
                           @input="update('email', $event.target.value)"
                           @blur="$v.settings.email.$touch"
                           placeholder="Boss Email">
                    <div v-if="$v.settings.email.$dirty && $v.settings.email.required === false" class="alert alert-danger" role="alert">The email is required.</div>
                    <div v-if="$v.settings.email.$dirty && $v.settings.email.email === false" class="alert alert-danger" role="alert">Enter correct email.</div>
                </div>
            </div>
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
        </div>
    </div>

</template>

<script type="text/babel">
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validation from './validations/account-info'

    export default {
        name: 'settings-acount-info',
        mixins: [vuelidateAnyerror],
        components: {},
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
            }
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$emit('update:validAccount', !this.$anyerror)
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