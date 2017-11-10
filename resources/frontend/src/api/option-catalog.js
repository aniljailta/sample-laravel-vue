import Vue from 'vue'
import {csrfToken} from './_config'

const optionCatalogResource = Vue.resource('/api/option-catalog{/id}',
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
        return optionCatalogResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return optionCatalogResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return optionCatalogResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return optionCatalogResource.delete({id: item.id})
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/option-catalog/active-flags', {
            params: query
        })
    },
    categories ({query}) {
        return Vue.http.get('/api/option-catalog/categories/', {
            params: query
        })
    },
    getForceQuantityFlags({query}) {
        return Vue.http.get('/api/option-catalog/force-quantity-flags', {
            params: query
        })
    },
    getConstraintTypes({query}) {
        return Vue.http.get('/api/option-catalog/constraint-types', {
            params: query
        })
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/option-catalog/update-sort-id', {
            params: data
        })
    },
    getManufacturerCompanies() {
        return Vue.http.get('/api/option-catalog/manufacturer-companies')
    }
}