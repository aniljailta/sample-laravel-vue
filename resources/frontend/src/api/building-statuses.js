/**
 * Communicates with API server about building models
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const buildingStatusResource = Vue.resource('/api/building-statuses{/id}',
  {},
  {
    getToPrioritize: {
      method: 'GET',
      url: '/api/building-statuses/to-prioritize/{building_id}/{status_type}'
    }
  },
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ id, query }) {
    return buildingStatusResource.get({ id, ...query })
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return buildingStatusResource.save({}, data)
    } else {
      data.append('_method', 'put')
      return buildingStatusResource.save({
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return buildingStatusResource.delete({ id: item.id })
  },
  getActiveFlags({ query }) {
    return Vue.http.get('/api/building-statuses/active-flags', {
      params: query
    })
  },
  getToPrioritize({ building_id, status_type }) {
    return buildingStatusResource.getToPrioritize({ building_id, status_type })
  },
  updatePriorities({ data }) {
    return Vue.http.get('/api/building-statuses/update-priorities', {
      params: data
    })
  }
}
