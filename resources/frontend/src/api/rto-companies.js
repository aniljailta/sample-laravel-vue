/**
 * Communicates with API server about rto companies
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const rtoCompanyResource = Vue.resource('/api/rto-companies{/id}',
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
        return rtoCompanyResource.get({id, ...query})
    },
    getRtoCompanies () {
        return rtoCompanyResource.get()
    }
}
