import Vue from 'vue'
import {csrfToken} from './_config'

const styleCatalogResource = Vue.resource('/api/style-catalog{/id}',
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
        return styleCatalogResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return styleCatalogResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return styleCatalogResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return styleCatalogResource.delete({id: item.id})
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/style-catalog/active-flags', {
            params: query
        })
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/style-catalog/update-sort-id', {
            params: data
        })
    },
    getManufacturerCompanies() {
        return Vue.http.get('/api/style-catalog/manufacturer-companies')
    }
}