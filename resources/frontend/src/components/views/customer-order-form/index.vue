<template>

    <div id="customer-order-form">
        <div class="container">
            <div class="page-header">
                <h1>Shed Builder and Quote Form</h1>
            </div>
        </div>

        <div id="loading" ref="loading" class="container text-center" v-show="mainLoading">
            <p class="text-muted">Loading...</p>
            <i class="fa fa-circle-o-notch fa-spin fa-5x text-muted"></i>
        </div>

        <div ref="sended" v-show="sended && !sending" class="container generated text-info">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-envelope-o"></i> Emailed!</h3>
                </div>
                <div class="panel-body">
                    Your quote has been emailed to you for future reference. <br>
                    An Urban Shed Concepts sales representative will contact you within 24 hours via your request.
                    <br>
                    <div class="row text-center" style="margin-top: 0.5em">
                        <a class="btn btn-default" @click.prevent="resetForm()">All Done</a>
                    </div>
                </div>
            </div>
        </div>

        <div ref="sending" v-show="sending" class="container generating text-success">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-circle-o-notch fa-spin"></i> Sending...</h3>
                </div>
                <div class="panel-body">
                    Your quote is being sent, this will take just a second.
                </div>
            </div>
        </div>


            <div class="container" v-show="showMainForm" ref="customerForm">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="list-group">
                                    <div class="list-group-item nb">
                                        <div class="text-center">
                                            <button class="btn btn-default"
                                                    style="margin-bottom: 0.5em"
                                                    v-on:click.prevent="nextStep('previous')"><i class="fa fa-arrow-left fa-fw"></i>Previous
                                            </button>
                                            <button class="btn"
                                                    style="margin-bottom: 0.5em"
                                                    v-bind:class="buttonSuggesting()"
                                                    v-on:click.prevent="nextStep('next')">Next<i class="fa fa-arrow-right fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'dealer'">
                                        <h4 class="list-group-item-heading">Select the nearest dealer location to minimize delivery charges:</h4>
                                        <step-dealer ref="stepDealer" v-bind:dealer.sync="dealer" v-on:data-ready="enableForm('dealers')"></step-dealer>
                                    </div>
                                    <div class="list-group-item" v-if="subStep == 'building-package'">
                                        <building-package-categories :building-packages="buildingPackages"
                                                                     :placeholder="true"
                                                                     @go-to-step="bpStartFromScratch"
                                                                     @select-building-package="selectBuildingPackage"/>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'building-style'">
                                        <h4 class="list-group-item-heading">Please select a building style:</h4>
                                        <div class="btn-group btn-group-lg1" data-toggle="buttons">
                                            <label v-for="style in buildingStyles"
                                                   v-bind:class="{'active': buildingStyle == style.id}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="buildingStyle"
                                                       v-bind:value="style.id"
                                                       v-on:click="nextStep('next')"
                                                       v-model="buildingStyle">
                                                <img class="img-responsive1" v-bind:src="style.image ? style.image.publicPath : null" width="60" height="60" v-bind:alt="style.name">
                                                {{ style.name }}
                                            </label>
                                        </div>
                                        <div v-if="$v.buildingStyle.$dirty && !$v.buildingStyle.required" class="alert alert-danger" role="alert">Building style is required.</div>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'building-dimension'">
                                        <h4 class="list-group-item-heading">Please select a building dimension:</h4>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label v-for="dimension in currentBuildingDimensions"
                                                   v-bind:class="{'active': buildingDimension == dimension.id}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="buildingDimension"
                                                       v-bind:value="dimension.id"
                                                       v-on:click="nextStep('next')"
                                                       v-model="buildingDimension">
                                                {{ dimension.width }}x{{ dimension.length }}
                                            </label>
                                        </div>
                                        <div v-if="$v.buildingDimension.$dirty && !$v.buildingDimension.required" class="alert alert-danger" role="alert">Building dimension is required.</div>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'custom-build-options'">
                                        <h5>Selected Options:</h5>

                                        <custom-build-options-modal ref="cboModal"
                                                                    v-on:update-custom-build-options="setCustomBuildOptions"
                                                                    v-on:select-building-package="selectBuildingPackage"
                                                                    v-bind:building-package="buildingPackage"
                                                                    v-bind:building-dimension="currentBuildingDimension"
                                                                    v-bind:custom-build-options="customBuildOptions"
                                                                    v-bind:options="options"
                                                                    v-bind:option-categories="optionCategories">
                                        </custom-build-options-modal>

                                        <option-viewer ref="optionViewer"
                                                       v-bind:validation="getValidation()"
                                                       v-bind:total-options="totalCustomOptions"
                                                       v-bind:building-options="customBuildOptions">
                                        </option-viewer>

                                        <div v-if="$v.currentRequiredCategories.$dirty && !$v.currentRequiredCategories.maxLength"
                                             v-for="(optionCategory, i) in currentRequiredCategories"
                                             v-bind:key="optionCategory.name">
                                            <div class="alert alert-danger"
                                                 role="alert"
                                                 style="margin-bottom: 0.5em">
                                                Custom builds require a minimum of one {{ optionCategory.name }} option.
                                            </div>
                                        </div>

                                        <p class="text-right">
                                            <button type="button"
                                                    v-on:click.prevent="openCustomBuildOptionsModal"
                                                    class="btn btn-danger">
                                                Edit Your Options
                                            </button>

                                            <button type="button"
                                                    v-bind:class="buttonSuggesting()"
                                                    v-on:click.prevent="nextStep('next')"
                                                    class="btn btn-default">
                                                Next<i class="fa fa-arrow-right fa-fw"></i>
                                            </button>
                                        </p>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'customer-info'">
                                        <h4 class="list-group-item-heading">Customer information:</h4>
                                        <step-customer ref="stepCustomer" v-bind:customer.sync="customer"></step-customer>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'similar-buildings'">
                                        <step-similar-buildings ref="stepSimilarBuildings"></step-similar-buildings>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'confirm-emailing'">
                                        <h5>All right - you are all done! <br>
                                            If you would like to change something on your building, click on it in the Quote Summary.
                                            Otherwise, click below to send the finished quote to your email or start a new quote.</h5>
                                        <div class="btn-group btn-group-lg1" data-toggle="buttons">
                                            <label v-bind:class="{'active': confirmEmailing === 'yes'}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="confirmEmailing"
                                                       value="yes"
                                                       v-on:click="nextStep('next')"
                                                       v-model="confirmEmailing">
                                                Email quote
                                            </label>
                                            <label v-bind:class="{'active': confirmEmailing === 'no'}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="confirmEmailing"
                                                       value="no"
                                                       v-on:click="resetForm()"
                                                       v-model="confirmEmailing">
                                                Start a new quote
                                            </label>
                                        </div>

                                        <div v-if="$v.confirmEmailing.$dirty && !$v.confirmEmailing.accepted" class="alert alert-warning" role="alert">Confirm emailing should be accepted if you want to get the quote.</div>
                                        <div v-if="$v.confirmEmailing.$dirty && !$v.confirmEmailing.required" class="alert alert-danger" role="alert">Confirm emailing is required.</div>
                                    </div>
                                    <div class="list-group-item" v-show="subStep == 'contact-info'">
                                        <h4>Best way to contact me is via:</h4>
                                        <div class="btn-group btn-group-lg1" data-toggle="buttons">
                                            <label v-for="(contactTypeVal, contactTypeId) in contactTypes"
                                                   v-bind:class="{'active': contactTypeId === contact.type}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="contactType"
                                                       v-bind:value="contactTypeId"
                                                       v-model="contact.type">
                                                {{ contactTypeVal }}
                                            </label>
                                        </div>
                                        <div v-if="$v.contact.type.$dirty && !$v.contact.type.required" class="alert alert-danger" role="alert">Contact type is required.</div>

                                        <h4>Best time to contact me is:</h4>
                                        <div class="btn-group btn-group-lg1" data-toggle="buttons">
                                            <label v-for="(contactTimeVal, contactTimeId) in contactTimes"
                                                   v-bind:class="{'active': contactTimeId === contact.time}"
                                                   class="btn btn-default">
                                                <input type="radio"
                                                       name="contactTime"
                                                       v-bind:value="contactTimeId"
                                                       v-model="contact.time">
                                                {{ contactTimeVal | ucfirst }}
                                            </label>
                                        </div>
                                        <div v-if="$v.contact.time.$dirty && !$v.contact.time.required" class="alert alert-danger" role="alert">Contact time is required.</div>

                                        <div class="row text-center">
                                            <button class="btn btn-primary"
                                                    style="margin-top: 1em"
                                                    @click.prevent="sendQuote()">Send to email <i class="fa fa-arrow-right fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <h4>Shed Builder Summary</h4>
                                <div class="list-group">

                                    <!-- Order Total -->
                                    <div class="list-group-item list-group-item-success lead main" v-if="currentTotal > 0">
                                        <div class="row">
                                            <div class="col-xs-6 text-right">Retail Price:</div>
                                            <div class="col-xs-6"><strong>{{ currentTotal | money }}</strong></div>
                                        </div>
                                    </div>

                                    <!-- Tax -->
                                    <div class="list-group-item list-group-item-success lead main" v-if="currentTotal > 0">
                                        <div class="row">
                                            <div class="col-xs-6 text-right">Tax:</div>
                                            <div class="col-xs-6"><strong>{{ tax | money }}</strong></div>
                                        </div>
                                    </div>

                                    <!-- Building total -->
                                    <div class="list-group-item list-group-item-info lead main" v-if="currentTotal > 0">
                                        <div class="row">
                                            <div class="col-xs-6 text-right">Building Total:</div>
                                            <div class="col-xs-6"><strong>{{ buildingTotal | money }}</strong></div>
                                        </div>
                                    </div>

                                    <!-- Rent-to-Own Opts -->
                                    <div class="list-group-item list-group-item-warning main" v-if="currentTotal > 0">
                                        <div class="row">
                                            <div class="col-xs-12 text-center"><strong>Rent-to-Own Options</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 text-right">Security Deposit:</div>
                                            <div class="col-xs-6"><strong>{{ securityDeposit | money }}</strong></div>
                                        </div>

                                        <div class="row" v-for="(rtoPayment, termId) in rtoPayments">
                                            <div class="col-xs-6 text-right">{{ termId }}-month payment:</div>
                                            <div class="col-xs-6"><strong>{{ rtoPayment | money }}</strong></div>
                                        </div>
                                    </div>

                                    <div class="list-group-item text-center" v-if="currentTotal > 0">
                                        <small>Retail price includes delivery and setup (prices are subject to change, please contact your nearest dealer for current pricing)</small>
                                    </div>

                                    <!-- Dealer -->
                                    <a href="#" v-bind:class="[subStep == 'dealer' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('dealer')">
                                        Dealer <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$refs.stepDealer && $refs.stepDealer.$v.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="dealer && dealer !== null">{{ dealer.businessName }}</span>
                                    </a>

                                    <!-- Building Package -->
                                    <a href="#" v-bind:class="[subStep == 'building-package' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('building-package')">
                                        Building Package <small class="hidden-xs">(click to change)</small>
                                        <span class="badge notice" v-if="buildingPackage">{{ buildingPackage.name }}</span>
                                    </a>

                                    <!-- Building Style -->
                                    <a href="#" v-bind:class="[subStep == 'building-style' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('building-style')">
                                        Building Style <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$v.buildingStyle.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="currentBuildingStyle && currentBuildingStyle !== null">{{ currentBuildingStyle.name }}</span>
                                    </a>

                                    <!-- Building Dimensions -->
                                    <a href="#" v-bind:class="[subStep == 'building-dimension' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('building-dimension')" v-if=" buildingStyle !== null ">
                                        Building Dimension <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$v.buildingDimension.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="currentBuildingDimension && currentBuildingDimension !== null">{{ currentBuildingDimension.width }}x{{ currentBuildingDimension.length }}</span>
                                    </a>

                                    <!-- Custom Build Options -->
                                    <a href="#" v-bind:class="[subStep == 'custom-build-options' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('custom-build-options')" v-if=" buildingStyle !== null && buildingDimension !== null">
                                        Building Options <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$v.currentRequiredCategories.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="customBuildOptions.length > 0">{{ customBuildOptions.length }} option(s) selected</span>
                                    </a>

                                    <!-- Confirm Emailing -->
                                    <a href="#" v-bind:class="[subStep == 'confirm-emailing' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('confirm-emailing')">
                                        Send to email <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$v.confirmEmailing.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="confirmEmailing !== null">{{ confirmEmailing | ucfirst }}</span>
                                    </a>

                                    <!-- Customer -->
                                    <a href="#" v-bind:class="[subStep == 'customer-info' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('customer-info')" v-if="confirmEmailing === 'yes'">
                                        Customer <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$refs.stepCustomer && $refs.stepCustomer.$v.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="customer && (customer.firstName !=='' || customer.lastName !=='')">{{ customer.firstName }} {{ customer.lastName }}</span>
                                        <div class="clearfix"></div>
                                    </a>

                                    <!-- Similar Buildings -->
                                    <a href="#" v-bind:class="[subStep == 'similar-buildings' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('similar-buildings')" v-if=" buildingStyle !== null && buildingDimension !== null">
                                        Similar Buildings <small class="hidden-xs">(click to view)</small>
                                        <span class="badge" v-if="totalSimilarBuildings">{{ totalSimilarBuildings }}</span>
                                    </a>

                                    <!-- Contact -->
                                    <a href="#" v-bind:class="[subStep == 'contact-info' ? 'active' : '']" class="list-group-item" @click.prevent="goToStep('contact-info')" v-if="confirmEmailing === 'yes'">
                                        Contact <small class="hidden-xs">(click to change)</small>
                                        <span class="pull-right" v-if="$v.contact.$error">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
                                        <span class="badge" v-if="contact && (contact.type !==null || contact.time !==null)">{{ currentContactType }} {{ currentContactTime }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

    </div>

</template>

<script type="text/babel">
    /*global swal*/
    import validationCustomerOrderForm from 'src/validations/customer-order-form/index.js'
    import validationCustomBuildOptions from 'src/validations/customer-order-form/custom-build-options.js'

    import stepDealer from './StepDealer.vue'
    import stepCustomer from './StepCustomer.vue'
    import stepSimilarBuildings from './StepSimilarBuildings.vue'
    import BuildingPackageCategories from 'src/components/views/partials/building-packages/BuildingPackageCategories.vue'

    import OptionViewer from 'src/components/views/partials/OptionViewer/OptionViewer.vue'
    import CustomBuildOptionsModal from './option-picker/CustomBuildOptionsModal.vue'
    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'
    import extractBuildingOptions from 'src/helpers/option-picker/extract-building-options'

    import apiBuildingPackages from 'src/api/building-packages'
    import apiOrders from 'src/api/orders'

    export default {
        mixins: [manageOptionPickerDataMixin],
        data() {
            return {
                alias: {
                    'buildingOptions': 'customBuildOptions'
                },
                dataLoaded: [],
                syncBuildingPackage: false,
                mainLoading: true,
                showMainForm: false,
                sending: false,
                sended: false,
                subStep: 'dealer',
                rtoTerms: {},
                dealer: {
                    id: null,
                    businessName: null,
                    businessAddress: null,
                    phoneNumber: null,
                    email: null,
                    taxRate: null,
                    depositType: null,
                    cashSaleDepositRate: null
                },
                customer: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    phoneNumber: '',
                    address: '',
                    city: '',
                    state: '',
                    zip: '',
                    buildingInSameAddress: false,
                    buildingLocationAddress: '',
                    buildingLocationCity: '',
                    buildingLocationState: '',
                    buildingLocationZip: ''
                },
                buildingPackage: null,
                buildingStyle: null,
                buildingStyles: {},
                buildingDimension: null,
                customBuildOptions: [],
                options: {},
                optionCategories: {},
                buildingPackages: [],
                confirmEmailing: null,
                contactTypes: {
                    phone: 'Phone',
                    email: 'Email'
                },
                contactTimes: {
                    anytime: 'Anytime',
                    after_5pm: 'After 5 p.m.',
                    weekends_only: 'Weekends only'
                },
                contact: {
                    type: null,
                    time: null
                }
            }
        },
        mounted() {
            this.$http.get('/api/styles', {
                params: {
                    per_page: 9999,
                    where: {
                        is_active: 'yes'
                    },
                    include: {
                        image: true,
                        building_models: {
                            where: {
                                is_active: 'yes'
                            },
                            order_by: ['width asc', 'length asc']
                        }
                    }
                }
            }).then((response) => {
                return response.data.data
            }).then((json) => {
                this.buildingStyles = json
                this.enableForm('buildingStyles')
            })

            this.$http.get('/api/options', {
                params: {
                    role: 'customer',
                    per_page: 9999,
                    where: {
                        is_active: 'yes'
                    },
                    include: {
                        category: true,
                        files: true,
                        allowable_models: {
                            where: {
                                is_active: 'yes'
                            },
                            fields: ['id']
                        },
                        allowable_colors: true,
                        'allowable_colors.image': true
                    }
                }
            }).then((response) => {
                return response.data.data
            }).then((json) => {
                this.options = json
                this.enableForm('options')
            })

            this.$http.get('/api/options/categories/?role=customer').then((response) => {
                return response.data
            }).then((json) => {
                this.optionCategories = json
                this.enableForm('optionCategories')
            })

            this.$http.get('/data/rto_terms.json').then((response) => {
                return response.data
            }).then((json) => {
                this.rtoTerms = json
                this.enableForm('rtoTerms')
            })

            apiBuildingPackages.get({
                query: {
                    per_page: 9999,
                    where: {
                        is_active: 'yes'
                    },
                    include: {
                        'building_model.style': {
                            fields: ['id', 'name', 'description']
                        },
                        'building_model.style.image': true,
                        'category': {
                            fields: ['id', 'name', 'description']
                        },
                        'category.files': true,
                        'options.option.allowable_colors': true,
                        'options.option.allowable_colors.image': true,
                        'options.option.allowable_models': {
                            fields: ['id', 'shell_price', 'name', 'description']
                        },
                        'files': true
                    }
                }
            }).then((response) => {
                this.buildingPackages = response.data.data
                this.enableForm('buildingPackages')
            })
        },
        components: {
            stepDealer,
            stepCustomer,
            stepSimilarBuildings,
            OptionViewer,
            CustomBuildOptionsModal,
            BuildingPackageCategories
        },
        computed: {
            currentBuildingStyle () {
                if (this.buildingStyle == null) {
                    return null
                }

                return _.find(this.buildingStyles, { id: _.toInteger(this.buildingStyle) })
            },
            currentBuildingDimension () {
                if (this.buildingDimension == null || this.buildingStyle == null) {
                    return null
                }

                var currentModelId = this.buildingDimension
                return _.find(this.currentBuildingStyle.buildingModels, function (item) {
                    return item.id === _.toInteger(currentModelId)
                })
            },
            currentBuildingDimensions () {
                if (this.buildingStyle == null) {
                    return []
                }

                let currentStyleID = this.buildingStyle
                let currentStyle = _.find(this.buildingStyles, function (item) {
                    return item.id === _.toInteger(currentStyleID)
                })
                return currentStyle.buildingModels || {}
            },
            totalCustomOptions () {
                if (this.customBuildOptions.length === 0) {
                    return 0
                }

                return _.reduce(this.customBuildOptions, function (memo, option) {
                    return memo + (option.unitPrice * option.quantity)
                }, 0, this)
            },
            currentContactType() {
                if (this.contact.type == null) {
                    return null
                }

                return this.contactTypes[this.contact.type]
            },
            currentContactTime() {
                if (this.contact.time == null) {
                    return null
                }

                return this.contactTimes[this.contact.time]
            },
            totalSimilarBuildings() {
                if (_.isArray(this.$refs.stepSimilarBuildings.similarBuildings)) {
                    return this.$refs.stepSimilarBuildings.similarBuildings.length
                }
                return false
            },
            currentTotal() {
                var total = 0
                let totalOption = this.totalCustomOptions
                // Custom Order

                // Get Base Model Price
                if (this.currentBuildingDimension) {
                    total += this.currentBuildingDimension.shellPrice
                }

                total += totalOption
                return total
            },
            tax() {
                if (this.dealer && this.dealer.taxRate !== null) {
                    return this.currentTotal * (this.dealer.taxRate / 100)
                }
                return 0
            },
            buildingTotal() {
                return (this.currentTotal + this.tax)
            },
            securityDeposit() {
                if (this.currentBuildingDimension !== null) {
                    var width = this.currentBuildingDimension.width
                    if (width <= 8) return 150
                    if (width > 8 && width <= 10) return 200
                    if (width > 10 && width <= 12) return 250
                    if (width > 12 && width <= 14) return 300
                }
                return 0
            },
            rtoPayments() {
                let self = this
                let terms = {
                    24: 0,
                    36: 0,
                    48: 0,
                    60: 0
                }

                if (_.size(self.rtoTerms) === 0) return terms

                let rtoAmount = this.getRtoAmount()
                _.each(terms, function(value, key) {
                    let rtoAdvanceMRP = rtoAmount / self.rtoTerms[key]['rtoFactor']
                    let rtoSalesTax = rtoAdvanceMRP * (self.dealer.taxRate / 100)
                    let rtoTotalAdvanceMRP = rtoAdvanceMRP + rtoSalesTax
                    terms[key] = rtoTotalAdvanceMRP
                })
                return terms
            },
            currentRequiredCategories() {
                let buildingOptions = this.customBuildOptions
                let modelID = this.currentBuildingDimension
                let categories = this.optionCategories

                if (modelID == null) return []

                let cats = _.filter(categories, (category) => {
                    // search only required categories
                    if (category.isRequired === true) {
                        // if option already selected - remove from required categories list
                        return (_.findIndex(buildingOptions, (buildingOption) => buildingOption.option.categoryId === category.id) === -1)
                    }
                    return false
                })
                return cats || []
            }
        },
        watch: {
            buildingStyle(buildingStyle, oldBuildingStyle) {
                if (this.syncBuildingPackage) return false
                this.buildingDimension = null
                this.buildingPackage = {}
            },
            buildingDimension(buildingDimension, oldBuildingDimension) {
                if (this.syncBuildingPackage) return false

                // get available default options
                let availableOptions = _.filter(this.options, function (item) {
                    return _.includes(_.map(item.allowableModels, 'id'), buildingDimension)
                }, this)

                // set default options
                let customBuildOptions = []
                for (let item of availableOptions) {
                    if (item.isDefault === 1) {
                        let defaultColor = _.find(item.allowableColors, {id: _.parseInt(item.defaultColorId)})
                        let quantity = 1
                        switch (item.forceQuantity) {
                            case 'building_length':
                                quantity = _.parseInt(this.currentBuildingDimension.length)
                                break
                            case 'wall_area':
                                quantity = (_.parseInt(this.currentBuildingDimension.length) + _.parseInt(this.currentBuildingDimension.width)) * _.parseInt(this.currentBuildingDimension.wallHeight)
                                break
                            case 'floor_area':
                                quantity = _.parseInt(this.currentBuildingDimension.length) * _.parseInt(this.currentBuildingDimension.width)
                                break
                        }

                        customBuildOptions.push({
                            option: item,
                            optionId: item.id,
                            quantity: quantity,
                            unitPrice: item.unitPrice,
                            color: defaultColor,
                            minQuantity: quantity
                        })
                    }
                }

                this.buildingPackage = {}
                this.customBuildOptions = customBuildOptions
            }
        },
        methods: {
            setCustomBuildOptions(customBuildOptions) {
                this.customBuildOptions = customBuildOptions
            },
            openCustomBuildOptionsModal() {
                this.$refs.cboModal.$emit('open-modal')
            },
            enableForm(el) {
                this.dataLoaded.push(el)
                // Check if all data was loaded
                var allGood = _.every([
                    'buildingStyles',
                    'options',
                    'optionCategories',
                    'dealers',
                    'rtoTerms',
                    'buildingPackages'
                ], el => (this.dataLoaded.indexOf(el) !== -1), this)

                // If it was, enable the form
                if (allGood) {
                    // $(this.$refs.loading).hide()
                    // $(this.$refs.customerForm).fadeIn()
                    this.mainLoading = false
                    this.showMainForm = true
                    this.$refs.stepDealer.showMap = true
                }
            },
            goToStep(step) {
                this.subStep = step
                return true
            },
            nextStep(direction) {
                if (direction === 'next') {
                    if (!this.validateStep(this.subStep, { $touch: true })) return null

                    if (this.subStep === 'dealer') return this.goToStep('building-package')
                    if (this.subStep === 'building-package') return this.goToStep('building-style')
                    if (this.subStep === 'building-style') return this.goToStep('building-dimension')
                    if (this.subStep === 'building-dimension') return this.goToStep('custom-build-options')
                    if (this.subStep === 'custom-build-options') return this.goToStep('confirm-emailing')
                    if (this.subStep === 'confirm-emailing') return this.goToStep('customer-info')
                    if (this.subStep === 'customer-info') {
                        if (!_.isNull(this.$refs.stepSimilarBuildings.similarBuildings)) return this.goToStep('similar-buildings')
                        return this.goToStep('contact-info')
                    }
                    if (this.subStep === 'similar-buildings') return this.goToStep('contact-info')
                }

                if (direction === 'previous') {
                    if (this.subStep === 'similar-buildings') return this.goToStep('customer-info')
                    if (this.subStep === 'contact-info') {
                        if (!_.isNull(this.$refs.stepSimilarBuildings.similarBuildings)) return this.goToStep('similar-buildings')
                        return this.goToStep('customer-info')
                    }
                    if (this.subStep === 'customer-info') return this.goToStep('confirm-emailing')
                    if (this.subStep === 'confirm-emailing') return this.goToStep('custom-build-options')
                    if (this.subStep === 'custom-build-options') return this.goToStep('building-dimension')
                    if (this.subStep === 'building-dimension') return this.goToStep('building-style')
                    if (this.subStep === 'building-style') return this.goToStep('building-package')
                    if (this.subStep === 'building-package') return this.goToStep('dealer')
                }

                return null
            },
            validateAll() {
                var subSteps = [
                    'dealer',
                    'building-style',
                    'building-dimension',
                    'custom-build-options',
                    'confirm-emailing',
                    'customer-info',
                    'contact-info'
                ]

                var invalidSteps = _.filter(subSteps, subStep => {
                    return !this.validateStep(subStep)
                })
                return _.isEmpty(invalidSteps)
            },
            // run/check values from batch of $v fields/or sub-components
            validateStep(subStep) {
                var subStepFields = {
                    'building-style': 'buildingStyle',
                    'building-dimension': 'buildingDimension',
                    'confirm-emailing': 'confirmEmailing',
                    'contact-info': 'contact'
                }

                if (!_.isUndefined(this.$v[subStepFields[subStep]])) {
                    this.$v[subStepFields[subStep]].$touch()
                    return !this.$v[subStepFields[subStep]].$error
                }

                if (subStep === 'dealer') {
                    this.$refs.stepDealer.$v.$touch()
                    return !this.$refs.stepDealer.$v.$error
                }

                if (subStep === 'custom-build-options') {
                    this.$v.currentRequiredCategories.$touch()
                    return !this.$v.currentRequiredCategories.$error
                }

                if (subStep === 'customer-info') {
                    this.$refs.stepCustomer.$v.$touch()
                    return !this.$refs.stepCustomer.$v.$error
                }

                return true
            },
            getValidation() {
                return validationCustomBuildOptions
            },
            selectBuildingPackage(item) {
                let self = this
                let buildingPackage = _.cloneDeep(item)

                if (_.isEmpty(item)) {
                    this.buildingPackage = item
                    return
                }

                this.syncBuildingPackage = true
                this.$nextTick(function () {
                    new Promise(function (resolve) {
                        let buildingStyle = _.find(self.buildingStyles, { id: buildingPackage.buildingModel.style.id })

                        // get available default options
                        let availableOptions = _.filter(self.options, function (item) {
                            return _.includes(_.map(item.allowableModels, 'id'), buildingPackage.buildingModelId)
                        }, this)

                        let defaultOptions = []
                        for (let item of availableOptions) {
                            if (item.isDefault === 1) {
                                let defaultColor = _.find(item.allowableColors, {id: _.parseInt(item.defaultColorId)})
                                let quantity = 1
                                switch (item.forceQuantity) {
                                    case 'building_length':
                                        quantity = _.parseInt(buildingPackage.buildingModel.length)
                                        break
                                    case 'wall_area':
                                        quantity = (_.parseInt(buildingPackage.buildingModel.length) + _.parseInt(buildingPackage.buildingModel.width)) * _.parseInt(buildingPackage.buildingModel.wallHeight)
                                        break
                                    case 'floor_area':
                                        quantity = _.parseInt(buildingPackage.buildingModel.length) * _.parseInt(buildingPackage.buildingModel.width)
                                        break
                                }

                                defaultOptions.push({
                                    option: item,
                                    optionId: item.id,
                                    quantity: quantity,
                                    unitPrice: item.unitPrice,
                                    color: defaultColor,
                                    minQuantity: quantity
                                })
                            }
                        }

                        // set default options
                        let customBuildOptions = []
                        for (let item of defaultOptions) {
                            if (buildingPackage.options.filter(function(e) { return e.option.categoryId === item.option.categoryId }).length === 0) {
                                customBuildOptions.push(item)
                            }
                        }

                        self.buildingPackage = buildingPackage
                        self.buildingStyle = buildingStyle.id
                        self.buildingDimension = buildingPackage.buildingModel.id
                        self.customBuildOptions = customBuildOptions
                        resolve()
                    }).then(function () {
                        _.each(buildingPackage.options, function(buildingPackageOption) {
                            self.addOption(buildingPackageOption.option, {
                                quantity: buildingPackageOption.quantity
                            })
                            /*
                            self.$refs.optionManager.$emit('option-manager-add-option', buildingPackageOption.option, {
                                quantity: buildingPackageOption.quantity
                            })
                            */
                        })
                    }).then(function () {
                        self.syncBuildingPackage = false
                        self.goToStep('custom-build-options')
                    })
                })
            },
            bpStartFromScratch() {
                this.selectBuildingPackage({})
                this.nextStep('next')
            },
            resetForm() {
                location.reload()
            },
            sendQuote() {
                if (!this.validateAll()) return

                var payload = {
                    form: 'customer',
                    _token: window.document.getElementById('_token').content,
                    // dealer
                    dealerId: this.dealer.id,
                    // custom order
                    buildingStyle: this.buildingStyle,
                    buildingDimension: this.buildingDimension,
                    customBuildOptions: extractBuildingOptions(this.customBuildOptions),
                    // customer
                    customer: this.customer,
                    confirmEmailing: this.confirmEmailing,
                    contactType: this.contact.type,
                    contactTime: this.contact.time
                }

                if (!_.isEmpty(this.buildingPackage)) {
                    payload.buildingPackage = this.buildingPackage.id
                }

                this.sending = true
                this.showMainForm = false
                this.response = {}
                apiOrders.generateCustomerOrder({payload: payload})
                    .then((response) => {
                        this.sending = false
                        this.sended = true
                    })
                    .catch(({ response, message }) => {
                        message = _.isObject(message) ? _.values(message) : message
                        let error = _.isArray(message) ? message.join('\r\n') : message
                        swal('Error', error, 'error')
                        // this.response.statusText = response.statusText
                        this.sending = false
                        this.showMainForm = true
                    })
            },
            getRtoAmount() {
                var rtoAmount = (
                        this.currentTotal // - ((this.netBuydown - this.securityDeposit) / (1 + (this.dealer.taxRate / 100)))
                )
                return rtoAmount
            },
            buttonSuggesting() {
                let currentStep = _.camelCase(this.subStep)
                if (this[currentStep] !== null) return { 'btn-warning': true }
                return { 'btn-default': true }
            }
        },
        validations: validationCustomerOrderForm
    }
</script>

<style type="text/sass" lang="scss" rel="stylesheet/scss">


</style>