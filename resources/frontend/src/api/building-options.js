/**
 * Communicates with API server about building options
 */

import Vue from 'vue'

export default {
  getAllBuildingOptions (beforeCb, successCb, errorCb) {
    const resource = Vue.resource('/api/building-models/options/dealer')
    beforeCb()
    return resource.get().then(
      (response) => {
        successCb(response.data)
      },
      (response) => {
        if (response.status === 422) {
          errorCb(response.data)
        } else {
          errorCb(response)
        }
      }
    )
  },
  getAllBuildingOptionCategories (beforeCb, successCb, errorCb) {
    const resource = Vue.resource('/api/options/categories/dealer')
    beforeCb()
    return resource.get().then(
      (response) => {
        successCb(response.data)
      },
      (response) => {
        if (response.status === 422) {
          errorCb(response.data)
        } else {
          errorCb(response)
        }
      }
    )
  }
}
