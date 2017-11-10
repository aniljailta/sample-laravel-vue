/**
 * Communicates with API server about colors
 */

import Vue from 'vue'

export default {
  getBuildingBodyColors () {
    return Vue.http.get('/api/colors-use/body/')
  },
  getBuildingTrimColors () {
    return Vue.http.get('/api/colors-use/trim/')
  },
  getBuildingRoofColors () {
    return Vue.http.get('/api/colors-use/roof/')
  }
}
