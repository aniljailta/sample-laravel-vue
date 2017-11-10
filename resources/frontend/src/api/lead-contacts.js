/**
 * Communicates with API server about buildings
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {
    leadContacts: {
        method: 'GET',
        url: '/api/order/lead-contacts'
    },
    customers: {
        method: 'GET',
        url: '/api/order/lead-contacts/customer'
    },
    orders: {
        method: 'GET',
        url: '/api/order/lead-contacts/order'
    }
}

const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const leadContactResource = Vue.resource('/api/lead-contacts{/id}', {}, actions, options)

export default {
    actions,
    leadContacts({query}) {
        return leadContactResource.leadContacts(query)
    },
    customers () {
        return leadContactResource.customers()
    },
    orders () {
        return leadContactResource.orders()
    }
}
