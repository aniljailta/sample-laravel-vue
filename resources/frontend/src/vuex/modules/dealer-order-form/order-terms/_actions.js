import {RECEIVE_RTO_TERMS} from './types'
import orderTerms from 'src/api/order-terms'

export default {
    getOrderRtoTerms ({commit}, {beforeCb, successCb, errorCb}) {
        orderTerms.getRtoTerms().then(response => {
            commit(RECEIVE_RTO_TERMS, response.data)
        })
    }
}
