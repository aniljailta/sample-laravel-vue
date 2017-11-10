/*eslint new-cap: 0 */

import {
  RECEIVE_FILE_CATEGORIES
} from '../types'
import files from 'src/api/files'

export default function ({ commit, state }, { query, beforeCb, successCb, errorCb }) {
  if (beforeCb) beforeCb()

  return files.categories({ query }).then(
    (response) => {
      commit(RECEIVE_FILE_CATEGORIES, response.data)

      if (successCb) successCb(response.data)
    },
    (response) => {
      if (response.status === 422) {
        if (errorCb) errorCb(response, response.data)
      } else {
        if (errorCb) errorCb(response, response.statusText)
      }
    }
  )
}
