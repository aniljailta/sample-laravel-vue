import types from './types'
import updateStoreState from 'src/helpers/update-store-state.js'

const state = function () {
    return {
        esignEmbed: {
            show: false,
            state: null
        },
        esignEmail: {
            show: false,
            state: null
        }
    }
}

const mutations = {
    [types.HIDE_ESIGN_EMBED](state) {
        state.esignEmbed.show = false
    },
    [types.SHOW_ESIGN_EMBED](state) {
        state.esignEmail.show = false
        state.esignEmbed.show = true
        state.esignEmbed.state = 'new'
    },
    [types.SET_STATE_ESIGN_EMBED](state, data, object) {
        state.esignEmbed = updateStoreState(state.esignEmbed, data, object)
    },
    [types.HIDE_ESIGN_EMAIL](state) {
        state.esignEmail.show = false
    },
    [types.SHOW_ESIGN_EMAIL](state) {
        state.esignEmbed.show = false
        state.esignEmail.show = true
        state.esignEmail.state = 'new'
    },
    [types.SET_STATE_ESIGN_EMAIL](state, data, object) {
        state.esignEmail = updateStoreState(state.esignEmail, data, object)
    },

    [types.HIDE_ALL](state) {
        state.esignEmbed.show = false
        state.esignEmail.show = false
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
