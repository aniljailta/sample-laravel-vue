/**
 * Communicates with API server about materials
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const materialResource = Vue.resource('/api/materials{/id}',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ id, query }) {
    return materialResource.get({ id, ...query })
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return materialResource.save({}, data)
    } else {
      data.append('_method', 'put')
      return materialResource.save({
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return materialResource.delete({ id: item.id })
  },
  getActiveFlags({ query }) {
    return Vue.http.get('/api/materials/active-flags', {
      params: query
    })
  }
}
