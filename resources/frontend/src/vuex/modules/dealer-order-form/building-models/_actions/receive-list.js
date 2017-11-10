/*eslint new-cap: 0 */

import {
  RECEIVE_BUILDING_MODELS
} from '../types'
import buildingModels from 'src/api/building-models'

export default function ({ commit, state }, { query, before, onSuccess, onError }) {
  if (before) before()

  return buildingModels.get({ query }).then(
    (response) => {
      commit(RECEIVE_BUILDING_MODELS, response.data.data)
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
