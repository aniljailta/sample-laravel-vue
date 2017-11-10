/**
 * Communicates with API server about dealers
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const resource = Vue.resource('/api/dealers{/id}',
    {},
    {
        // TODO: should be changed to primary get() later
        perId: {
            method: 'GET',
            url: '/api/dealers/per-id'
        },
        activeFlags: {
            method: 'GET',
            url: '/api/dealers/active-flags'
        }
    },
    {
        headers: {
            'X-CSRF-TOKEN': csrfToken()
        }
    }
)

export default {
    get ({ id, query }) {
        return resource.get({ id, ...query })
    },
    save ({ item, data }) {
        if (typeof item.id === 'undefined') {
            return resource.save({}, data)
        } else {
            data.append('_method', 'put')
            return resource.save({
                id: item.id
            }, data)
        }
    },
    delete({ item }) {
        return resource.delete({ id: item.id })
    },
    // TODO: should be changed to primary get() later
    getDealers (beforeCb, successCb, errorCb) {
        beforeCb()
        return resource.perId().then(
            (response) => {
                successCb(response.data)
            },
            (response) => {
                if (response.status === 422) {
                    errorCb(response.data)
                } else {
                    errorCb(response)
                }
            }
        )
    },
    activeFlags ({params}) {
        return resource.activeFlags(params)
    }
}
