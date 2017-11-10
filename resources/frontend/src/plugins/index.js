import Vuelidate from 'vuelidate'
import VueResource from 'vue-resource'
import ResourceCaseConverter from './vue-resource-case-converter'

// custom filters (until vue v2 is not released?)
import Filter from './filter.js'
import filters from 'src/filters/all.js'

function install (Vue, config) {
  Vue.use(Vuelidate)
  Vue.use(VueResource)
  Vue.use(ResourceCaseConverter)
  Vue.use(Filter, filters)
}

export default install
