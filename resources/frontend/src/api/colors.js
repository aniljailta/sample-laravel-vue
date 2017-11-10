/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const colorResource = Vue.resource('/api/colors{/id}',
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
        return Vue.http.get('/api/colors/types', {
            params: query
        })
    },
    getOptions({query}) {
        return Vue.http.get('/api/colors/options', {
            params: query
        })
    },
    getBuildingModels({query}) {
        return Vue.http.get('/api/colors/building-models', {
            params: query
        })
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/colors/active-flags', {
            params: query
        })
    },
    addColor({ data }) {
        return Vue.http.get('/api/colors/add-color', {
            params: data
        })
    },
    getCompanyAllowableColors() {
        return Vue.http.get('/api/colors/allowable-colors')
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/colors/update-sort-id', {
            params: data
        })
    }
}