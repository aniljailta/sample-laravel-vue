const settings = (state, search) => {
    if (typeof state.global === 'undefined') return {}
    if (typeof state.global[search.id] !== 'undefined') {
        return state.global[search.id]
    }
    return null
}

const settingsGlobal = state => {
    return state.global
}

export default {
    settings,
    settingsGlobal
}