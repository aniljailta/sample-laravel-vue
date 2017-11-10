/**
 * Communicates with API server about files
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const fileResource = Vue.resource('/api/files{/id}',
    {},
    {
        categories: {
            method: 'GET',
            url: '/api/files/categories'
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
        return fileResource.get({id, ...query})
    },
    categories({query}) {
        return fileResource.categories({
            params: query
        })
    },
    getByCategoryId(id) {
        return fileResource.get({
            where: {
                categoryId: id
            },
            order_by: 'id desc'
        })
    }
}
