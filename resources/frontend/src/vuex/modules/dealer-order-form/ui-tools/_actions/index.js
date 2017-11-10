import types from '../types'
// import { saveOrder } from './order.js'

export default {
    updateExpensesTool({ commit }, params) {
        commit(types.UPDATE_EXPENSE_TOOL, params)
    },
    /* uiToolsSaveForm({ commit, state }) {
      saveOrder(
        { commit, state },
        {
          beforeCb: () => { commit(SHOW_SAVE_FORM_TOOL) }
        }
      )
    },*/
    uiToolsShowSaveForm({commit}) {
        commit(types.SHOW_SAVE_FORM_TOOL)
    },
    uiToolsHideSaveForm({commit}) {
        commit(types.HIDE_SAVE_FORM_TOOL)
    },
    uiToolsSetStateSaveForm({commit}, params) {
        commit(types.SET_STATE_SAVE_FORM_TOOL, params)
    },

    uiToolsShowLoadForm({commit}) {
        commit(types.SHOW_LOAD_FORM_TOOL)
    },
    uiToolsHideLoadForm({commit}) {
        commit(types.HIDE_LOAD_FORM_TOOL)
    },
    uiToolsSetStateLoadForm({commit}, dataState) {
        commit(types.SET_STATE_LOAD_FORM_TOOL, dataState)
    },

    uiToolsSetStateInventoryForm({commit}, data, object) {
        commit(types.SET_STATE_INVENTORY_FORM_TOOL, data, object)
    },

    uiToolsShowCancellationRequestForm({commit}) {
        commit(types.SHOW_CANCELLATION_REQUEST_FORM_TOOL)
    },
    uiToolsHideCancellationRequestForm({commit}) {
        commit(types.HIDE_CANCELLATION_REQUEST_FORM_TOOL)
    },
    uiToolsSetStateCancellationRequestForm({commit}, params) {
        commit(types.SET_STATE_CANCELLATION_REQUEST_FORM_TOOL, params)
    },
    showChangeOrderModal({commit}) {
        commit(types.SHOW_CHANGE_FORM_TOOL)
    },
    hideChangeOrderModal({commit}) {
        commit(types.HIDE_CHANGE_FORM_TOOL)
    },
    setStateChangeOrderModal({commit}) {
        commit(types.SET_STATE_CHANGE_FORM_TOOL)
    },

    uiToolsHideAllModals({commit}) {
        commit(types.HIDE_ALL_MODAL_FORM_TOOL)
    }
}
