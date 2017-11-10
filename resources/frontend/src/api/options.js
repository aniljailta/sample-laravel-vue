/**
 * Communicates with API server about options
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const optionResource = Vue.resource('/api/options{/id}',
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
        return optionResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return optionResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return optionResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return optionResource.delete({id: item.id})
    },
    categories ({query}) {
        return Vue.http.get('/api/options/categories/', {
            params: query
        })
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/options/active-flags', {
            params: query
        })
    },
    getForceQuantityFlags({query}) {
        return Vue.http.get('/api/options/force-quantity-flags', {
            params: query
        })
    },
    getConstraintTypes({query}) {
        return Vue.http.get('/api/options/constraint-types', {
            params: query
        })
    },
    addOption({ data }) {
        return Vue.http.get('/api/options/add-option', {
            params: data
        })
    },
    getCompanyAllowableOptions() {
        return Vue.http.get('/api/options/allowable-options')
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/options/update-sort-id', {
            params: data
        })
    }
}
