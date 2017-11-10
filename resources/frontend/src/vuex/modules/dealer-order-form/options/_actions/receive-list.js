/*eslint new-cap: 0 */

import {
  RECEIVE_OPTIONS
} from '../types'
import options from 'src/api/options'

export default function ({ commit, state }, { query, before, onSuccess, onError }) {
  if (before) before()
  return options.get({ query }).then(
    (response) => {
      commit(RECEIVE_OPTIONS, response.data.data)
      if (onSuccess) onSuccess(response.data)
    },
    (response) => {
      if (response.status === 422) {
        if (onError) onError(response, response.data)
      } else {
        if (onError) onError(response, response.statusText)
      }
    }
  )
}
