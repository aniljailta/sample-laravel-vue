/**
 * Communicates with API server about building history
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const buildingLocationResource = Vue.resource('/api/building/{building_id}/locations{/id}',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ building_id, id, query }) {
    return buildingLocationResource.get({ building_id, id, ...query })
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return buildingLocationResource.save({
        building_id: item.buildingId
      }, data)
    } else {
      data.append('_method', 'put')
      return buildingLocationResource.save({
        building_id: item.buildingId,
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return buildingLocationResource.delete({
      building_id: item.buildingId,
      id: item.id
    })
  }
}
