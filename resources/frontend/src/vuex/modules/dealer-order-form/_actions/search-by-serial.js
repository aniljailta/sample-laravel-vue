/*eslint new-cap: 0 */

import buildings from 'src/api/buildings'

export default function ({ commit, state }, { params, beforeCb, successCb, errorCb }) {
  if (beforeCb) beforeCb()

  return buildings.searchBuildingBySerial({ params }).then(
    (response) => {
      if (successCb) successCb(response)
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
