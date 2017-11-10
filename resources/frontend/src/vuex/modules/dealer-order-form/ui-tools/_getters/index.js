const getExpensesTool = state => {
    return state.expensesTool
}

const getUiToolsShowLoadForm = state => {
    return state.loadForm.show
}

const showChangeOrderModal = state => {
    return state.changeForm.show
}

const stateChangeOrderModal = state => {
    return state.changeForm.state
}

const getUiToolsStateLoadForm = state => {
    return state.loadForm.state
}

const getUiToolsSaveForm = state => {
    return state.saveForm
}

const getUiToolsShowSaveForm = state => {
    return state.saveForm.show
}

const getUiToolsStateSaveForm = state => {
    return state.saveForm.state
}

const getUiToolsShowInventoryForm = state => {
    return state.inventoryForm.show
}

const getUiToolsStateInventoryForm = state => {
    return state.inventoryForm.state
}

const getUiToolsShowCancellationRequestForm = state => {
    return state.cancellationRequestForm.show
}

const getUiToolsStateCancellationRequestForm = state => {
    return state.cancellationRequestForm.state
}

export default {
    getExpensesTool,
    getUiToolsShowLoadForm,
    getUiToolsStateLoadForm,
    getUiToolsSaveForm,
    getUiToolsShowSaveForm,
    getUiToolsStateSaveForm,
    getUiToolsShowInventoryForm,
    getUiToolsStateInventoryForm,
    showChangeOrderModal,
    stateChangeOrderModal,
    getUiToolsShowCancellationRequestForm,
    getUiToolsStateCancellationRequestForm
}