/**
 * Communicates with API server about sales
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {
    // required for calculate URL for export data to xls,csv
    get: {
        method: 'GET',
        url: '/api/sales{/id}'
    },
    statuses: {
        method: 'GET',
        url: '/api/sales/statuses'
    },
    sendEmail: {
        method: 'POST',
        url: '/api/sales/send-email/{id}'
    }
}

const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const saleResource = Vue.resource('/api/sales{/id}', {}, actions, options)

export default {
    actions,
    get ({id, query}) {
        return saleResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return saleResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return saleResource.save({
                id: item.id
            }, data)
        }
    },
    listSales ({query}) {
        return Vue.http.get('/api/sales', {
            params: query
        })
    },
    statuses () {
        return saleResource.statuses()
    },
    saveSale ({item}) {
        if (typeof item.id === 'undefined') {
            return saleResource.save({}, item)
        } else {
            return saleResource.update({
                id: item.id
            }, item)
        }
    },
    deleteSale ({item}) {
        return saleResource.delete({id: item.id})
    },
    sendEmail ({item, params}) {
        return saleResource.sendEmail({
            id: item.id
        }, params)
    }
}
