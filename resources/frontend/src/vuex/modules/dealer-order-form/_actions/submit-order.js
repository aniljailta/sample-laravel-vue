import {
  SYNC_START,
  SYNC_SUCCESS,
  SYNC_FAILURE
} from '../types'

import orders from 'src/api/orders'
import getOrderFormFields from './get-order-form-fields'

export default function({ commit, state }, { item, beforeCb, successCb, errorCb }) {
  if (beforeCb) beforeCb()
  commit(SYNC_START, 'submitting')

  item = getOrderFormFields({ state }, item)
  return orders.submitOrder({ item }).then(
    (response) => {
      if (successCb) successCb(response)
      commit(SYNC_SUCCESS, {syncStatus: 'submitted', response: response.data, payload: item})
    },
    (response) => {
      if (response.status === 422) {
        if (errorCb) errorCb(response, response.data)
        commit(SYNC_FAILURE, {syncStatus: 'submitError', response, message: response.data})
      } else {
        if (errorCb) errorCb(response, response.statusText)
        commit(SYNC_FAILURE, {syncStatus: 'submitError', response, message: response.statusText})
      }
    }
  )
}
