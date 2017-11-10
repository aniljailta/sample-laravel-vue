import Vue from 'vue'
import types from './types'
import updateStoreState from 'src/helpers/update-store-state.js'

const state = function () {
    return {
        expensesTool: {
            showBuildingPrices: false,
            showOrderOptionPrices: false,
            showTaxes: false,
            showAmountDues: false
        },
        loadForm: {
            show: false,
            state: null
        },
        saveForm: {
            show: false,
            state: null
        },
        inventoryForm: {
            show: false,
            state: null
        },
        changeForm: {
            show: false,
            state: null
        },
        cancellationRequestForm: {
            show: false,
            state: null
        }
    }
}

const mutations = {
    [types.UPDATE_EXPENSE_TOOL] (state, changes) {
        Vue.set(state, 'expensesTool', {...state.expensesTool, ...changes})
    },
    [types.HIDE_LOAD_FORM_TOOL] (state) {
        state.loadForm.show = false
    },
    [types.SHOW_LOAD_FORM_TOOL] (state) {
        state.saveForm.show = false
        state.changeForm.show = false
        state.cancellationRequestForm.show = false
        state.loadForm.show = true
        state.loadForm.state = 'new'
    },
    [types.SET_STATE_LOAD_FORM_TOOL] (state, dataState) {
        state.loadForm.state = dataState
    },

    [types.HIDE_CHANGE_FORM_TOOL] (state) {
        state.changeForm.show = false
    },
    [types.SHOW_CHANGE_FORM_TOOL] (state) {
        state.saveForm.show = false
        state.loadForm.show = false
        state.changeForm.show = true
        state.changeForm.state = 'new'
    },
    [types.SET_STATE_CHANGE_FORM_TOOL] (state, dataState) {
        state.loadForm.state = dataState
    },

    [types.HIDE_SAVE_FORM_TOOL](state) {
        state.saveForm.show = false
        state.saveForm.onContinue = null
    },
    [types.SHOW_SAVE_FORM_TOOL](state) {
        state.loadForm.show = false
        state.changeForm.show = false
        state.cancellationRequestForm.show = false
        state.saveForm.show = true
        state.saveForm.state = 'new'
    },
    [types.SET_STATE_SAVE_FORM_TOOL](state, params) {
        state.saveForm = updateStoreState(state.saveForm, params)
    },

    [types.SET_STATE_INVENTORY_FORM_TOOL] (state, data, object) {
        state.inventoryForm = updateStoreState(state.inventoryForm, data, object)
    },

    [types.HIDE_ALL_MODAL_FORM_TOOL] (state) {
        state.loadForm.show = false
        state.saveForm.show = false
        state.inventoryForm.show = false
        state.cancellationRequestForm.show = false
        state.changeForm.show = false
    },

    [types.HIDE_CANCELLATION_REQUEST_FORM_TOOL] (state) {
        state.cancellationRequestForm.show = false
        state.cancellationRequestForm.onContinue = null
    },
    [types.SHOW_CANCELLATION_REQUEST_FORM_TOOL] (state) {
        state.loadForm.show = false
        state.saveForm.show = false
        state.saveForm.show = false
        state.cancellationRequestForm.show = true
        state.cancellationRequestForm.state = 'new'
    },
    [types.SET_STATE_CANCELLATION_REQUEST_FORM_TOOL] (state, params) {
        state.cancellationRequestForm = updateStoreState(state.cancellationRequestForm, params)
    }
}

import actions from './_actions'
import getters from './_getters'

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}

