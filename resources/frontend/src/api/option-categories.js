/**
 * Communicates with API server about option categories
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const optionCategoryResource = Vue.resource('/api/option-categories{/id}',
  {},
  {
    groups: {
      method: 'GET',
      url: '/api/option-categories/groups'
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
    return optionCategoryResource.get({ id, ...query })
  },
  save ({ item, data }) {
    if (typeof item.id === 'undefined') {
      return optionCategoryResource.save({}, data)
    } else {
      data.append('_method', 'put')
      return optionCategoryResource.save({
        id: item.id
      }, data)
    }
  },
  delete({ item }) {
    return optionCategoryResource.delete({ id: item.id })
  },
  groups ({ params }) {
    return optionCategoryResource.groups(params)
  },
  updateSortId({ data }) {
      return Vue.http.get('/api/option-categories/update-sort-id', {
          params: data
      })
  }
}
