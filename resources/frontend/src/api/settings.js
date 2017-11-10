/**
 * Communicates with API server about settings
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const settingResource = Vue.resource('/api/settings{/id}',
    {},
    {
        timezones: {
            method: 'GET',
            url: '/api/settings/timezones'
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
        return settingResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return settingResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return settingResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return settingResource.delete({id: item.id})
    },
    timezones() {
        return settingResource.timezones()
    }
}
