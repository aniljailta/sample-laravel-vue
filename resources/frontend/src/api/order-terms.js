/**
 * Communicates with API server about order terms
 */

import Vue from 'vue'

export default {
  getRtoTerms () {
    const resource = Vue.resource('/data/rto_terms.json')
    return resource.get()
  }
}
