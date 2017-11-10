<template>

    <section class="panel-featured page-list-items">
        <!--
        <header class="panel-heading clearfix">
            <h2 class="panel-title">Settings</h2>
        </header>
        -->

        <div class="panel-body overlayable">
            <data-process :process="dataProcess" :with_loader="true" :mode="'row'"></data-process>

            <div v-if="dataIsReady">
                <div class="settings-tabs">
                    <div class="list-group col-xs-12">
                        <a class="list-group-item"
                           @click="showTab('companyInfo')"
                           :class="[tab == 'companyInfo' ? 'active' : '']">
                            Company Information <span v-if="validCompany === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                        </a>
                        <a class="list-group-item"
                           @click="showTab('accountInfo')"
                           :class="[tab === 'accountInfo' ? 'active' : '']">
                            Account Information <span v-if="validAccount === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                        </a>
                        <a class="list-group-item"
                           @click="showTab('rtoInfo')"
                           :class="[tab === 'rtoInfo' ? 'active' : '']">
                            Rent-to-own Information <span v-if="validRto === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                        </a>
                        <a class="list-group-item"
                           @click="showTab('orderInfo')"
                           :class="[tab === 'orderInfo' ? 'active' : '']">
                            Order Settings <span v-if="validOrder === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                        </a>
                        <a class="list-group-item"
                           @click="showTab('deliveryInfo')"
                           :class="[tab === 'deliveryInfo' ? 'active' : '']">
                            Delivery Settings <span v-if="validDelivery === false">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                        </a>
                    </div>
                </div>

                <div class="col-xs-12">
                    <company-info ref="companyInfo" v-show="tab === 'companyInfo'" :settings.sync="company" :validCompany.sync="validCompany"/>
                    <account-info ref="accountInfo" v-show="tab === 'accountInfo'" :settings.sync="account" :validAccount.sync="validAccount"/>
                    <rto-info ref="rtoInfo" v-show="tab === 'rtoInfo'" :settings.sync="rto" :validRto.sync="validRto"/>
                    <order-info ref="orderInfo" v-show="tab === 'orderInfo'" :settings.sync="order" :validOrder.sync="validOrder"/>
                    <delivery-info ref="deliveryInfo" v-show="tab === 'deliveryInfo'" :settings.sync="delivery" :validDelivery.sync="validDelivery"/>
                </div>

                <div class="col-xs-12 mt-md">
                    <button class="btn btn-success" @click="saveSettings">Apply settings</button>
                </div>
            </div>
        </div>
    </section>

</template>

<script type="text/babel">
    import {mapActions} from 'vuex'
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import CompanyInfo from './tabs/CompanyInfo.vue'
    import AccountInfo from './tabs/AccountInfo.vue'
    import RtoInfo from './tabs/RtoInfo.vue'
    import OrderInfo from './tabs/OrderInfo.vue'
    import DeliveryInfo from './tabs/DeliveryInfo.vue'

    import apiCompanySettings from 'src/api/company-settings'
    import apiUsers from 'src/api/users'
    import apiFiles from 'src/api/files'

    export default {
        name: 'settings',
        extends: BaseDataItem,
        components: {CompanyInfo, AccountInfo, RtoInfo, OrderInfo, DeliveryInfo},
        data() {
            return {
                tab: 'companyInfo',
                validCompany: null,
                validAccount: null,
                validRto: null,
                validOrder: null,
                validDelivery: null,
                company: {
                    name: null,
                    address: null,
                    city: null,
                    state: null,
                    zip: null,
                    phone: null,
                    email: null,
                    website: null,
                    facebook: null,
                    instagram: null,
                    gplus: null,
                    pinterest: null,
                    logo: null,
                    mailHost: null,
                    mailPort: null,
                    mailUsername: null,
                    mailFrom: null,
                    mailPassword: null,
                    mailEncryption: null,
                    perPage: null,
                    timeZone: null
                },
                account: {
                    id: null,
                    firstName: null,
                    lastName: null,
                    title: null,
                    email: null,
                    phone: null
                },
                rto: {
                    rtoIsUsed: null,
                    rtoCompanyId: null
                },
                delivery: {
                    deliveryDispatch: null,
                    deliveryContactName: null,
                    deliveryContactPhone: null,
                    deliveryContactEmail: null
                },
                order: {
                    estimatedDeliveryPeriod: null,
                    leadTime: null,
                    initialContactEligibility: null,
                    changeOrderFee: null,
                    // invoiceGeneratedEmail: null,
                    footnote: null,
                    include3d: null
                },
                dataProcess: {
                    text: 'Loading..'
                }
            }
        },
        computed: {},
        created() {
            this.$validateAll({
                $touch: true
            })
        },
        methods: {
            ...mapActions({
                fetchUser: 'user/fetchUser',
                fetchSettings: 'settings/fetchSettings'
            }),
            save(tab, {item, data}) {
                if (tab === 'accountInfo') {
                    return apiUsers.save({item, data}).then(response => {
                        this.fetchUser()
                        return response.data
                    })
                }

                return apiCompanySettings.save({item, data}).then(response => {
                    this.fetchSettings()
                    return response.data
                })
            },
            getTabData(tab) {
                if (tab === 'companyInfo') {
                    return {...this.company}
                }
                if (tab === 'accountInfo') {
                    return {...this.account}
                }
                if (tab === 'rtoInfo') {
                    return {...this.rto}
                }
                if (tab === 'deliveryInfo') {
                    return {...this.delivery}
                }
                if (tab === 'orderInfo') {
                    return {...this.order}
                }
            },
            saveSettings() {
                let tab = this.tab
                // validate all settings for notice user about another issues on this page
                this.$validateAll({$touch: true})
                if (!this.$validate([tab], {$touch: true})) return false

                // send only validated data (tab)
                let item = this.getTabData(tab)
                this.run({text: 'Saving..', type: 'form'})

                return this.save(tab, {item, data: item}).then(response => {
                    this.$emit('data-ready', {success: response.msg})
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            showTab(tab) {
                this.tab = tab
            },
            initData() {
                this.initDependencies()
                    .then(response => {
                        this.$emit('data-ready')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initDependencies() {
                const datas = [
                    apiCompanySettings.get({}),
                    apiUsers.account(),
                    apiFiles.getByCategoryId('inventory_form_footer_graphic_1'),
                    apiFiles.getByCategoryId('inventory_form_footer_graphic_2')
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.company = _.pick(response[0].data, _.keys(this.company))
                        this.account = _.pick(response[1].data, _.keys(this.account))
                        this.rto = _.pick(response[0].data, _.keys(this.rto))
                        this.order = _.pick(response[0].data, _.keys(this.order))
                        this.delivery = _.pick(response[0].data, _.keys(this.delivery))
                        // order inventory form footers
                        this.order.inventoryFormFooter1 = _.first(response[2].data.data)
                        this.order.inventoryFormFooter2 = _.first(response[3].data.data)
                    })
            },
            $validate(refs, options) {
                options = options || {}
                let validRefs = _.filter(refs, ref => {
                    if (this.$refs[ref] && !_.isUndefined(this.$refs[ref].$v)) {
                        if (options.$reset) this.$refs[ref].$v.$reset()
                        if (options.$touch) this.$refs[ref].$v.$touch()
                        return !this.$refs[ref].$anyerror
                    }
                    return true
                })

                return (_.isEqual(validRefs, refs))
            },
            $validateAll(options) {
                let allValid = this.$validate([
                    'companyInfo',
                    'accountInfo',
                    'orderInfo',
                    'rtoInfo',
                    'deliveryInfo'
                ], options)

                return allValid
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/pages/settings.scss"></style>
<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>