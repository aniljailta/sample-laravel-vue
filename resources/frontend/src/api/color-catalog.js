/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const colorResource = Vue.resource('/api/color-catalog{/id}',
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
        return colorResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return colorResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return colorResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return colorResource.delete({id: item.id})
    },
    getTypes({query}) {
        return Vue.http.get('/api/color-catalog/types', {
            params: query
        })
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/color-catalog/active-flags', {
            params: query
        })
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/color-catalog/update-sort-id', {
            params: data
        })
    },
    getManufacturerCompanies() {
        return Vue.http.get('/api/color-catalog/manufacturer-companies')
    }
}