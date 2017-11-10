/*eslint new-cap: 0 */

import Vue from 'vue'
import types from './types'
import convertKeys from 'convert-keys'

const state = {
    user: {},
    company: {},
    role: {},
    roles: []
}

const mutations = {
    [types.UPDATE_USER] (state, data) {
        Vue.set(state, 'user', convertKeys.toCamel(data))
    },
    [types.UPDATE_ROLES] (state, data) {
        Vue.set(state, 'roles', convertKeys.toCamel(data))
        Vue.set(state, 'role', convertKeys.toCamel(_.first(data)))
    },
    [types.UPDATE_COMPANY] (state, data) {
        Vue.set(state, 'company', convertKeys.toCamel(data))
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
