import * as types from './types'
// import updateStoreState from 'src/helpers/update-store-state.js'

const state = function() {
  return {
      list: []
  }
}

const mutations = {
  [types.RECEIVE_BUILDING_PACKAGES] (state, list) {
    state.list = list
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

