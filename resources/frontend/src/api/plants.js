/**
 * Communicates with API server about plants
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const plantResource = Vue.resource('/api/plants{/id}',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({id, query}) {
    return plantResource.get({id, ...query})
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return plantResource.save({}, data)
    } else {
      // data.append('_method', 'put')
      return plantResource.save({
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return plantResource.delete({ id: item.id })
  }
}
