/*eslint new-cap: 0 */

import Vue from 'vue'
import types from './types'
import updateStoreState from 'src/helpers/update-store-state.js'

import buildingModels from './building-models'
import options from './options'
import buildingPackages from './building-packages'
import orderTerms from './order-terms'
import dealers from './dealers'
import styles from './styles'
import files from './files'
import settings from './settings'
import esign from './esign'
import uiTools from './ui-tools'
import collectDeposit from './collect-deposit'

const state = function() {
    return {
        state: {
            form: null, // dealer | customer
            mode: 'draft', // draft | submit
            sync: {
                date: null,
                status: null, // saving-saved, loading-loaded, submitting-submitted error
                merging: null, // to avoid some reactive changes/clearings
                message: null,
                hash: {
                    last: null, // calculated on save/load order form
                    next: null // caclulate for checking data changes before generate documents
                }
            }
        },

        current: {},

        dealer: {
            id: null,
            businessName: null,
            businessAddress: null,
            phoneNumber: null,
            email: null,
            taxRate: null,
            depositType: null,
            cashSaleDepositRate: null,
            salesPerson: null
        },
        customer: {
            learningAboutUs: null,
            learningAboutUsOther: null,
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
        building: {
            buildingCondition: null,
            saleType: null,
            buildingPackage: null,
            buildingStyle: null,
            buildingDimension: null,
            serial: '',
            inventoryBuilding: {
                serial: null,
                price: 0,
                securityDeposit: 0,
                options: []
            },
            customBuildOptions: []
        },
        order: {
            date: new moment().format('MM/DD/YYYY'),
            type: null,
            paymentType: null,
            paymentMethod: null,
            changeOrderFee: null,
            amountReceived: null,
            mfDepositReceived: null,
            rtoDepositReceived: null,
            transactionId: null,
            deliveryRemarks: {
                levelPad: false,
                softWhenWet: false,
                widthRestrictions: false,
                heightRestrictions: false,
                mustCrossNeighboringProperty: false,
                requiresSiteVisit: false,
                notes: null
            },
            customerExpectsDate: {},
            rtoType: null,
            rtoTerm: null,
            promo99: false,
            signatureMethodId: null,
            salesTaxRate: null,
            options: []
        },
        renter: {
            coRenterFirstName: '',
            coRenterLastName: '',
            propertyOwnership: 'own',
            landlordFullName: '',
            landlordPhoneNumber: '',
            textAllowed1: 'no',
            cellPhoneNumber2: '',
            textAllowed2: 'no',
            homePhoneNumber: '',
            emailInsteadOfMail: true,
            renterEmployer: '',
            renterEmployerPhoneNumber: '',
            renterEmployerPhoneExtension: '',
            renterSupervisor: '',
            renterSupervisorOccupation: '',

            coRenterEmployer: '',
            coRenterEmployerPhoneNumber: '',
            coRenterEmployerPhoneExtension: '',
            coRenterSupervisor: '',
            coRenterSupervisorOccupation: '',

            reference1Name: '',
            reference1Relationship: '',
            reference1PhoneNumber: '',
            reference1Address: '',
            reference1City: '',
            reference1State: '',
            reference1Zip: '',
            reference2Name: '',
            reference2Relationship: '',
            reference2PhoneNumber: '',
            reference2Address: '',
            reference2City: '',
            reference2State: '',
            reference2Zip: ''
        },
        attachments: [],
        summary: {
            order: {},
            building: {}
        },
        validation: {
            dealer: null,
            building: null,
            order: null,
            attachments: null,
            submit: null,
            final: null
        }
    }
}

const mutations = {
    [types.SET_STATE] (state, data) {
        state.state = updateStoreState(state.state, data)
    },
    [types.SYNC_UPDATE] (state, data) {
        state.state.sync = {...state.state.sync, ...data}
    },
    [types.SYNC_START] (state, syncType) {
        state.state.sync.status = syncType
        state.state.sync.merging = 'idle'
        state.state.sync.message = null
    },
    [types.SYNC_SUCCESS] (state, {syncStatus, response, payload}) {
        state.state.sync.status = syncStatus

        if (syncStatus === 'saved' || syncStatus === 'loaded' || syncStatus === 'submitted') {
            state.current = response.state
            state.current.loadedAt = Date.now()
            if (!_.isUndefined(response.attachments)) {
                state.attachments = response.attachments
            }

            if (syncStatus === 'saved') {
                state.customer = _.merge(state.customer, response.customer)
                if (payload['saveAs'] === 'new') {
                    state.attachments = []
                }
            }

            if (syncStatus === 'loaded') {
                state.state.sync.merging = 'running'
                state.dealer = response.dealer
                state.customer = response.customer
                state.building = response.building
                state.order = response.order
                state.renter = response.renter
                state.order.date = new moment().format('MM/DD/YYYY')
                // state.current = updateStoreState(state.state, response.state)
            }
        }
    },
    [types.SYNC_FAILURE] (state, {syncStatus, response, message}) {
        state.state.sync.status = syncStatus

        if (message) {
            state.state.sync.message = message
        } else {
            if (_.isObject(response) && response.statusText) {
                state.state.sync.message = response.statusText
            } else {
                state.state.sync.message = response
            }
        }
    },
    [types.UPDATE_DEALER] (state, data) {
        state.dealer = updateStoreState(state.dealer, data)
    },
    [types.UPDATE_CUSTOMER] (state, data) {
        state.customer = updateStoreState(state.customer, data)
    },
    [types.UPDATE_BUILDING] (state, data, object) {
        state.building = updateStoreState(state.building, data)
    },
    [types.UPDATE_ORDER] (state, data) {
        _.each(data, (val, key) => {
            _.set(state.order, key, val)
        })
    },
    [types.UPDATE_RENTER] (state, data, object) {
        state.renter = updateStoreState(state.renter, data)
    },
    [types.UPDATE_SUMMARY] (state, data, object) {
        state.summary = updateStoreState(state.summary, data)
    },

    [types.ADD_ATTACHMENT] (state, newAttachment) {
        state.attachments.push(newAttachment)
    },

    [types.REMOVE_ATTACHMENT] (state, id) {
        let index = _.findIndex(state.attachments, function (el) {
            return el.id === id
        })

        state.attachments.splice(index, 1)
    },

    // Specific building data mutations
    [types.ADD_BUILDING_CUSTOM_OPTION] (state, newOption) {
        state.building.customBuildOptions.push(newOption)
    },
    [types.REMOVE_BUILDING_CUSTOM_OPTION] (state, option) {
    },

    // TODO deprecate?
    [types.UPDATE_BUILDING_CUSTOM_OPTION] (state, optionIndex, props) {
        let changedOption = {...state.building.customBuildOptions[optionIndex], ...props}
        state.building.customBuildOptions.splice(optionIndex, 1, changedOption)
    },
    [types.INCREASE_BUILDING_CUSTOM_OPTION] (state, optionIndex, extraProps) {
        let option = state.building.customBuildOptions[optionIndex]
        option.quantity = option.quantity + 1
        option.total = option.unitPrice * option.quantity
        _.forEach(extraProps, function (value, key) {
            option[key] = value
        })
    },
    [types.DECREASE_BUILDING_CUSTOM_OPTION] (state, optionIndex, extraProps) {
        let option = state.building.customBuildOptions[optionIndex]
        option.quantity = option.quantity - 1
        option.total = option.unitPrice * option.quantity
        _.forEach(extraProps, function (value, key) {
            option[key] = value
        })
    },

    // Per step validation mutations
    [types.UPDATE_VALIDATION] (state, data) {
        _.each(data, (value, step) => {
            state.validation[step] = value
        })
    },

    [types.UPDATE_ORDER_OPTIONS] (state, options) {
        Vue.set(state.order, 'options', options)
    }
}

const modules = {
    buildingModels,
    options,
    buildingPackages,
    orderTerms,
    dealers,
    styles,
    files,
    settings,
    esign,
    uiTools,
    collectDeposit
}

import actions from './_actions'
import getters from './_getters'

// avoid recursive import from getter/order-order <--> getter/index.js
import orderOrderFns from './_getters/order-order'
import orderBuildingFns from './_getters/order-building'

export default {
    namespaced: true,
    state,
    mutations,
    modules,
    actions,
    getters: {
       ...getters,
       ...orderOrderFns,
       ...orderBuildingFns
    }
}

