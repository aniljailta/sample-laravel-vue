/**
 * Communicates with API server about styles
 */

// import Vue from 'vue'

// export default {
//   get ({ query }) {
//     return Vue.http.get('/api/styles', {
//       params: query
//     })
//   }
// }

/**
 * Communicates with API server about styles
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const styleResource = Vue.resource('/api/styles{/id}',
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
        return styleResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return styleResource.save({}, data)
        } else {
            if (data instanceof FormData) data.append('_method', 'put')
            else data._method = 'put'

            return styleResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return styleResource.delete({id: item.id})
    },
    getActiveFlags({query}) {
        return Vue.http.get('/api/styles/active-flags', {
            params: query
        })
    },
    getCompanyAllowableStyles() {
        return Vue.http.get('/api/styles/allowable-styles')
    },
    updateSortId({ data }) {
        return Vue.http.get('/api/styles/update-sort-id', {
            params: data
        })
    }
}
