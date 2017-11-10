import types from './types'

const state = function() {
    return {
        list: {}
    }
}

const mutations = {
    [types.RECEIVE_BUILDING_MODELS] (state, items) {
        state.list = items
    }
}

import actions from './_actions'
import getters from './_getters.js'

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}

