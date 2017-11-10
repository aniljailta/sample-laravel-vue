import types from './types'

const state = function() {
    return {
        showModal: false,
        isValid: null
    }
}

const mutations = {
    [types.HIDE_COLLECT_DEPOSIT_MODAL] (state) {
        state.showModal = false
    },
    [types.SHOW_COLLECT_DEPOSIT_MODAL] (state) {
        state.showModal = true
    },
    [types.UPDATE_VALID_FLAG] (state, isValid) {
        state.isValid = isValid
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
