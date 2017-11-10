/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const truckResource = Vue.resource('/api/trucks{/id}',
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
        return truckResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return truckResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return truckResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return truckResource.delete({id: item.id})
    }
}