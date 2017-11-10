import types from './types'
import apiCompanySettings from 'src/api/company-settings'

const update = function({commit}, settings) {
    commit(types.UPDATE, settings)
}

const updateLogo = function({commit}, logo) {
    commit(types.UPDATE_LOGO, logo)
}

const fetchSettings = function({commit}, data) {
    return apiCompanySettings.get({}).then((response) => {
        commit(types.UPDATE, response.data)
    })
}

export default {
    update,
    updateLogo,
    fetchSettings
}
