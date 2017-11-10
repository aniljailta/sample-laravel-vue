import types from './types'

const state = function() {
  return {
      rtoTerms: {}
  }
}

const mutations = {
  [types.RECEIVE_RTO_TERMS] (state, rtoTerms) {
    state.rtoTerms = rtoTerms
  }
}

import actions from './_actions.js'
import getters from './_getters.js'

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
