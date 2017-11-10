import {
  HIDE_ESIGN_EMBED,
  SHOW_ESIGN_EMBED,
  SET_STATE_ESIGN_EMBED,

  HIDE_ESIGN_EMAIL,
  SHOW_ESIGN_EMAIL,
  SET_STATE_ESIGN_EMAIL,

  HIDE_ALL
} from '../types'

export default {
  esignEmbedShow ({ commit }) {
    commit(SHOW_ESIGN_EMBED)
  },
  esignEmbedHide ({ commit }) {
    commit(HIDE_ESIGN_EMBED)
  },
  esignEmbedSet ({ commit }, data, object) {
    commit(SET_STATE_ESIGN_EMBED, data, object)
  },
  esignEmailShow ({ commit }) {
    commit(SHOW_ESIGN_EMAIL)
  },
  esignEmailHide ({ commit }) {
    commit(HIDE_ESIGN_EMAIL)
  },
  esignEmailSet ({ commit }, data, object) {
    commit(SET_STATE_ESIGN_EMAIL, data, object)
  },
  esignHideAll ({ commit }) {
    commit(HIDE_ALL)
  }
}
