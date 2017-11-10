/**
 * Communicates with API server about building history
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const buildingHistoryResource = Vue.resource('/api/building/{building_id}/history{/id}',
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
    return buildingHistoryResource.get({ building_id, id, ...query })
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return buildingHistoryResource.save({
        building_id: item.buildingId
      }, data)
    } else {
      data.append('_method', 'put')
      return buildingHistoryResource.save({
        building_id: item.buildingId,
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return buildingHistoryResource.delete({
      building_id: item.buildingId,
      id: item.id
    })
  }
}
