/*eslint no-multiple-empty-lines: "off"*/
/*eslint no-unused-vars:0 */
import 'babel-polyfill'

import vue from 'vue'
import directives from 'src/directives'
import plugins from 'src/plugins'
import filters from 'src/filters'
import validators from 'src/validators'
import components from './components'
import config from 'src/../config'
import 'src/assets'

const Vue = vue
Vue.config.debug = config.debug
Vue.config.devtools = config.debug
Vue.config.productionTip = config.debug
// Vue.config.warnExpressionErrors = false

// eventHub - vue 2.0 campatibility preparation
const bus = new Vue()
Vue.prototype.$bus = bus

/*
 |------------------------------------------------
 | Plugins
 |------------------------------------------------
 */
Vue.use(plugins, config)

/*
 |------------------------------------------------
 | Directives
 |------------------------------------------------
 */
Vue.use(directives)

/*
 |------------------------------------------------
 | Filters
 |------------------------------------------------
 */
Vue.use(filters)

/*
 |------------------------------------------------
 | Validators
 |------------------------------------------------
 */
Vue.use(validators)

/*
 |------------------------------------------------
 | Components
 |------------------------------------------------
 | Attaching them to the root instance so they can
 | be used in all views without having to import
 */
Vue.use(components)

const store = require('src/vuex/store')
// console.log(store)

export default {
  Vue: Vue,
  store: store,
  initialize(App, Router) {
    if (Router) {
      return {
        root: new Vue({
          router: Router,
          components: { App },
          store, // inject store to all children
          el: '#v-app'
        })
      }
    } else {
      return {
        root: new Vue({
          store, // inject store to all children
          el: '#v-app'
        })
      }
    }
  }
}
