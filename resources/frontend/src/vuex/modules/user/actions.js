import types from './types'
import apiUsers from 'src/api/users'

const updateUser = function({commit}, user) {
    commit(types.UPDATE_USER, user)
}

const updateRoles = function({commit}, roles) {
    commit(types.UPDATE_ROLES, roles)
}

const fetchUser = function({commit}) {
    return apiUsers.account().then((response) => {
        commit(types.UPDATE_USER, response.data)
        commit(types.UPDATE_ROLES, response.data.roles)
        commit(types.UPDATE_COMPANY, response.data.company)
    })
}

export default {
    updateUser,
    updateRoles,
    fetchUser
}
