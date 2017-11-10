/*eslint no-multiple-empty-lines: "off"*/
/*eslint no-unused-vars:0 */
import 'babel-polyfill'

import vue from 'vue'
import { sync } from 'vuex-router-sync'
import directives from 'src/directives'
import plugins from 'src/plugins'
import filters from 'src/filters'
import validators from 'src/validators'
import config from 'src/../config'
import router from './pages/router'
import 'src/assets'

const Vue = vue
Vue.config.debug = config.debug
Vue.config.devtools = config.debug
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
// Vue.use(components)

const store = require('src/vuex/store')
const app = require('src/components/App.vue')
const appRouter = router(store)

sync(store, appRouter)

const Vm = new Vue({
    router: appRouter,
    components: { app },
    store, // inject store to all children
    el: 'app'
    /*
    props: ['params'],
    propsData: {
        params: window.laravel
    },
    render: h => h(app, {
        props: {
            params: window.laravel
        }
    })
    */
})