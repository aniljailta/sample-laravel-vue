/*eslint new-cap: 0 */

import Vue from 'vue'
import types from './types'
import convertKeys from 'convert-keys'

const state = {
    all: {}
}

const mutations = {
    [types.UPDATE] (state, data) {
        Vue.set(state, 'all', convertKeys.toCamel(data))
    },
    [types.UPDATE_LOGO] (state, logo) {
        Vue.set(state.all, 'logo', convertKeys.toCamel(logo))
    }
}

const modules = {}

import actions from './actions'
import getters from './getters'

export default {
    namespaced: true,
    state,
    mutations,
    modules,
    actions,
    getters
}
