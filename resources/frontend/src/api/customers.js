/**
 * Communicates with API server about users
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {}
const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const customerResource = Vue.resource('/api/customers{/id}', {}, actions, options)

export default {
    ...actions,
    get({id, query}) {
        return customerResource.get({id, ...query})
    },
    save({item, data}) {
        if (typeof item.id === 'undefined') {
            return customerResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return customerResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return customerResource.delete({id: item.id})
    }
}
