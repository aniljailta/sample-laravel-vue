import types from './types'
// import updateStoreState from 'src/helpers/update-store-state.js'

const state = function() {
  return {
      global: {}
  }
}

const mutations = {
  [types.RECEIVE_SETTINGS] (state, settings) {
    state.global = settings
    // state.global = updateStoreState(state.global, settings)
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
