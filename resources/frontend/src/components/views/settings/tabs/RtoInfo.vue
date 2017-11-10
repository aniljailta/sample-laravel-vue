<template>

    <div class="form">
        <div class="row">
            <div class="col-xs-12">
                <h4>Do you use rent-to-own?</h4>
                <div class="btn-group btn-toggle input-buttons btn-group-sm">
                    <label :class="{'active': settings.rtoIsUsed}" class="btn btn-default">
                        <input @click="update('rtoIsUsed', true)"
                               type="radio"
                               name="rtoIsUsed"
                               :checked="settings.rtoIsUsed"> Yes
                    </label>
                    <label :class="{'active': !settings.rtoIsUsed}" class="btn btn-default">
                        <input @click="update('rtoIsUsed', false)"
                               type="radio"
                               name="rtoIsUsed"
                               :checked="!settings.rtoIsUsed"> No
                    </label>
                </div>
            </div>
        </div>
        <!--
        <div class="row" v-if="settings.rtoIsUsed === '1' || settings.rtoIsUsed === true">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="rto-company">Rent-to-own company</label>
                    <select class="form-control" id="rto-company" :class="{ 'invalid': $v.settings.rtoCompanyId.$error }"
                            name="rto-company"
                            :value="settings.rtoCompanyId"
                            @blur="$v.settings.rtoCompanyId.$touch"
                            @change.prevent="update('rtoCompanyId', $event.target.value)">
                        <option v-for="(rtoCompany, indexId) in rtoCompanies" v-bind:value="rtoCompany.id">{{ rtoCompany.name }}</option>
                    </select>
                    <div v-if="$v.settings.rtoCompanyId.$dirty && $v.settings.rtoCompanyId.required === false" class="alert alert-danger">This field is required.</div>
                </div>
            </div>
        </div>
        -->
    </div>

</template>

<script type="text/babel">
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validation from './validations/rto-info'
    import apiRtoCompanies from 'src/api/rto-companies'

    export default {
        name: 'settings-rto-info',
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
            return {
                rtoCompanies: []
            }
        },
        created() {
            // this.getRtoCompanies()
            this.$watch('$anyerror', (value) => {
                this.$emit('update:validRto', !this.$anyerror)
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
            },
            getRtoCompanies() {
                return apiRtoCompanies.get({
                    query: {
                        per_page: 10000
                    }
                }).then((response) => {
                    this.rtoCompanies = response.data.data
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>