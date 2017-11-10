import types from './types'
// import updateStoreState from 'src/helpers/update-store-state.js'

const state = function () {
    return {
        list: [],
        list_pagination: {},
        list_search: {},
        list_state: {},
        selected: null
    }
}

const mutations = {
    [types.INIT_STYLES](state, list) {
        state.list = list
    },
    [types.RECEIVE_STYLES](state, list) {
        state.list = list
    },
    [types.RECEIVE_STYLES_PAGINATION](state, listPagination) {
        state.list_pagination = listPagination
    },
    [types.RECEIVE_STYLES_SEARCH](state, listSearch) {
        state.list_search = listSearch
    },
    [types.RECEIVE_STYLES_STATE](state, listState) {
        state.list_state = listState
    },
    [types.SELECT_STYLE](state, select) {
        state.selected = select
    },
    [types.UPDATE_STYLE](state, indexId, data) {
        // let object = state.list[indexId]
        // let newObject = { ...object, ...data }
        state.list.splice(indexId, 1, data)
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
