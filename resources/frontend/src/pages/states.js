// get user role from vuex state
const getUserState = (store) => {
    return new Promise((resolve, reject) => {
        if (_.isEmpty(store.state.user.role)) {
            const unwatch = store.watch(
                () => store.state.user.role,
                (value) => {
                    if (!_.isEmpty(value)) {
                        unwatch()
                        resolve(value)
                    }
                }
            )
        } else {
            resolve(store.state.user.role)
        }
    })
}

// get company role from vuex state
const getCompanyState = (store) => {
    return new Promise((resolve, reject) => {
        if (_.isEmpty(store.state.user.company)) {
            const unwatch = store.watch(
                () => store.state.user.company,
                (value) => {
                    if (!_.isEmpty(value)) {
                        unwatch()
                        resolve(value)
                    }
                }
            )
        } else {
            resolve(store.state.user.company)
        }
    })
}

export default {
    getUserState,
    getCompanyState
}