const all = state => {
    return state.all
}

const timezone = state => {
    return {
        value: state.all.timeZone
    } || {}
}

const logo = state => {
    if (state.all.logo && state.all.logo.publicPath) {
        return state.all.logo.publicPath
    }
}

export default {
    all,
    timezone,
    logo
}
