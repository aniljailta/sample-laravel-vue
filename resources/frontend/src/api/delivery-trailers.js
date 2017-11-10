/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const trailersResource = Vue.resource('/api/trailers{/id}',
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
        return trailersResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return trailersResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return trailersResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return trailersResource.delete({id: item.id})
    }
}