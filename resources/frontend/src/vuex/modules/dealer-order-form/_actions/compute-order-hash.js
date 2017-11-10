import { SYNC_UPDATE } from '../types'

import objectHash from 'object-hash'
import getOrderFormFields from './get-order-form-fields'

const computeOrderHash = function ({commit, state}, type) {
    let hash = objectHash(getOrderFormFields({state}), {
        algorithm: 'md5' // or sha1, but md5 should be faster
    })

    commit(SYNC_UPDATE, {
        hash: Object.assign({}, state.state.sync.hash, {[type]: hash})
    })
}

export default computeOrderHash