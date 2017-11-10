/**
 * Communicates with API server about dealer commission
 */
import Vue from 'vue'
import {csrfToken} from './_config'

const dealerCommissionResource = Vue.resource('/api/dealer-commission',
    {},
    {
        updateCommissionRate: {
            method: 'GET',
            url: '/api/dealer-commission/update-commission-rate'
        },
        updateAmountDue: {
            method: 'GET',
            url: '/api/dealer-commission/update-amount-due'
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
        return dealerCommissionResource.get({id, ...query})
    },
    updateCommissionRate({id, commissionRate}) {
        return dealerCommissionResource.updateCommissionRate({id, commissionRate})
    },
    updateAmountDue({id, amountDue}) {
        return dealerCommissionResource.updateAmountDue({id, amountDue})
    }
}