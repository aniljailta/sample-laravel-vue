import { RECEIVE_DEALERS } from '../types'
import dealers from 'src/api/dealers'

export default {
  getAllDealers ({commit}, params) {
    params = params || {}
    return dealers.get(params)
      .then(response => {
        commit(RECEIVE_DEALERS, response.data.data)
        return response.data
      })
      .catch(response => {
        if (response.status === 422) return Promise.reject(response.data)
        return Promise.reject(response.statusText)
    })
  }
}
