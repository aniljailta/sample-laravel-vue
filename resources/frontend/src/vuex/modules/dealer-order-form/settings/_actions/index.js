import {
    RECEIVE_SETTINGS
} from '../types'
import companySettings from 'src/api/company-settings'

export default {
    getAllSettings({commit}, query) {
        return companySettings.get(query).then(response => {
            commit(RECEIVE_SETTINGS, response.data)
        })
    }
}
