/**
 * Communicates with API server about company settings
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const companySettingResource = Vue.resource('/api/company/settings{/id}',
    {},
    {
        testEmail: {
            method: 'POST',
            url: '/api/company/settings/test-email'
        }
    },
    {
        headers: {
            'X-CSRF-TOKEN': csrfToken()
        }
    }
)

export default {
    get ({id, query}) {
        return companySettingResource.get({id, ...query})
    },
    save ({item, data}) {
        if (data instanceof FormData) data.append('_method', 'PUT')
        else data._method = 'PUT'

        return companySettingResource.save({
            id: item.id
        }, data)
    },
    testEmail(params) {
        return companySettingResource.testEmail(params)
    }
}
