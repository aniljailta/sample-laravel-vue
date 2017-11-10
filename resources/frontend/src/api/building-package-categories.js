/**
 * Communicates with API server about building packages
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const buildingPackageCategoriesResource = Vue.resource('/api/building-package-categories{/id}',
    {},
    {
        activeFlags: {
            method: 'GET',
            url: '/api/building-package-categories/active-flags'
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
        return buildingPackageCategoriesResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return buildingPackageCategoriesResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return buildingPackageCategoriesResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return buildingPackageCategoriesResource.delete({id: item.id})
    },
    activeFlags ({params}) {
        return buildingPackageCategoriesResource.activeFlags(params)
    }
}
