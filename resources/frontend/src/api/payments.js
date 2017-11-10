/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const paymentResource = Vue.resource('/api/payments{/id}',
    {},
    {},
    {
        headers: {
            'X-CSRF-TOKEN': csrfToken()
        }
    }
)

export default {
    get ({id, query}) {
        return paymentResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return paymentResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return paymentResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return paymentResource.delete({id: item.id})
    }
}