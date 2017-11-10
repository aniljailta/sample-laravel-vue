/*eslint new-cap: 0 */

import {
  RECEIVE_BUILDING_PACKAGES
} from '../types'
import buildingPackages from 'src/api/building-packages'

export default function ({ commit, state }, { query, before, onSuccess, onError }) {
  if (before) before()
  return buildingPackages.get({ query }).then(
    (response) => {
      commit(RECEIVE_BUILDING_PACKAGES, response.data.data)
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
