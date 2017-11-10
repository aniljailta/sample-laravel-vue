/*eslint new-cap: 0 */

import {
  RECEIVE_OPTION_CATEGORIES
} from '../types'
import options from 'src/api/options'

export default function ({ commit, state }, { query, before, onSuccess, onError }) {
  if (before) before()

  return options.categories({ query }).then(
    (response) => {
      commit(RECEIVE_OPTION_CATEGORIES, response.data)
      if (onSuccess) onSuccess(response)
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
