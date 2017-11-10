/**
 * Communicates with API server about building history
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const DashboardResource = Vue.resource('/api/dashboard',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ query }) {
    return DashboardResource.get({ query })
  },
  getData() {
    return Vue.http.get('/api/dashboard/get-data')
  }
}
