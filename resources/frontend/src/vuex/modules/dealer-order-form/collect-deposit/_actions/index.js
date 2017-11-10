import {
    HIDE_COLLECT_DEPOSIT_MODAL,
    SHOW_COLLECT_DEPOSIT_MODAL,
    UPDATE_VALID_FLAG
} from '../types'

export default {
    showModal({commit}) {
        commit(SHOW_COLLECT_DEPOSIT_MODAL)
    },
    hideModal({commit}) {
        commit(HIDE_COLLECT_DEPOSIT_MODAL)
    },
    updateValidFlag({commit}, value) {
        commit(UPDATE_VALID_FLAG, value)
    }
}
