/**
 * Communicates with API server about building packages
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const buildingPackageResource = Vue.resource('/api/building-packages{/id}',
    {},
    {
        activeFlags: {
            method: 'GET',
            url: '/api/building-packages/active-flags'
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
        return buildingPackageResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return buildingPackageResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return buildingPackageResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return buildingPackageResource.delete({id: item.id})
    },
    activeFlags ({params}) {
        return buildingPackageResource.activeFlags(params)
    }
}
