/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const buildingModelResource = Vue.resource('/api/building-models{/id}',
    {},
    {
        activeFlags: {
            method: 'GET',
            url: '/api/building-models/active-flags'
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
        return buildingModelResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return buildingModelResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return buildingModelResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return buildingModelResource.delete({id: item.id})
    },
    activeFlags ({ params }) {
        return buildingModelResource.activeFlags(params)
    },
    // TODO: remove and use this.activeFlags
    getActiveFlags({query}) {
        return Vue.http.get('/api/building-models/active-flags', {
            params: query
        })
    }
}
