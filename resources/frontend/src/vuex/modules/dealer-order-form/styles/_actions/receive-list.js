/*eslint new-cap: 0 */

import {
  RECEIVE_STYLES
} from '../types'
import styles from 'src/api/styles'

export default function ({ commit, state }, { query, before, onSuccess, onError }) {
  if (before) before()

  return styles.get({ query }).then(
    (response) => {
      commit(RECEIVE_STYLES, response.data.data)
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
